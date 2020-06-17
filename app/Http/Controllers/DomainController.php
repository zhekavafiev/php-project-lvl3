<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain;
use Illuminate\Pagination\Paginator as Paginator;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    public function show($id)
    {
        $domain = DB::select('select domains.id, name, domains.created_at,
            max(domain_checks.created_at) as last_check, h1, keywords, description, status_code
            from domains left join domain_checks
            on domains.id = domain_checks.domain_id
            group by name
            having domains.id = ?', [$id]);
        
        if (empty($domain)) {
            return abort(404);
        }

        $checks = DB::select('select id, created_at, updated_at, status_code, h1, keywords, description
            from domain_checks 
            where domain_id = ?', [$id]);
        
        return view('domain.show', compact('domain', 'checks'));
    }

    public function index()
    {
        $updateTable = DB::select('select domains.id, domains.name, domains.created_at, 
            max(domain_checks.created_at) as last_check, domain_checks.status_code
            from domains left join domain_checks
            on domains.id = domain_checks.domain_id
            group by name
            order by domains.id');
        // $chunks = array_chunk($updateTable, 3);
        // foreach ($chunks as $key => $value) {
        //     $paginator[$key + 1] = new Paginator($value, 3, $key + 1, [
        //         'path' => route('domains.index')
        //     ]);
        // }
        // $updateTable = Domain::paginate(3);
        // dd($updateTable);
        // $paginator = new Paginator($updateTable, count($updateTable), 3);
        // $updateTable = array_slice($paginator)
        // $paginator->setPath(route('domains.index'));

        return view('domain.index', [
            'table' => $updateTable,
            ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'url'
        ]);

        $parsedName = parse_url($request->input('name'));
        $name = "{$parsedName['scheme']}://{$parsedName['host']}";
        $domain = new Domain();
        try {
            $query = DB::select('Select id from domains where name = ?', [$name]);
            $id = $query[0]->id;
            session()->flash('message', "Domen {$name} has been checked early");
            return redirect()->route('domain', ['id' => $id]);
        } catch (\Exception $error) {
            $domain->name = $name;
            $domain->save();
            session()->flash('message', "Domain {$domain->name} has added");
            return redirect()->route('domains.show', $domain);
        }
    }
}
