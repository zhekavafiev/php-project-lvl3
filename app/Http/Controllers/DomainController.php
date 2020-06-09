<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    public function show($id)
    {
        $domain = DB::select('select * from domains where id = ?', [$id]);
        
        if (empty($domain)) {
            return abort(404);
        }

        $checks = DB::select('select id, created_at, updated_at, status_code, h1, keywords, description
            from domain_checks 
            where domain_id = ?', [$id]);
        
        $queryH1 = DB::select('select h1 
            from domain_checks 
            where domain_id = ? order by created_at desc limit 1', [$id]);
        $domain[0]->lastH1 = $queryH1[0]->h1 ?? null;
        
        $queryKeywords = DB::select('select keywords 
            from domain_checks 
            where domain_id = ? order by created_at desc limit 1', [$id]);
        $domain[0]->lastKeywords = $queryKeywords[0]->keywords ?? null;
        
        $queryDescription = DB::select('select description 
            from domain_checks 
            where domain_id = ? order by created_at desc limit 1', [$id]);
        $domain[0]->lastDescription = $queryDescription[0]->description  ?? null;

        return view('domain.show', [
            'table' => $domain,
            'checks' => $checks
            ]);
    }

    public function index()
    {
        $table = DB::table('domains')->get()->all();
        $updateTable = array_map(function ($domain) {
            $id = $domain->id;
            $lastCheck = DB::select(
                'select status_code
                from domain_checks
                where domain_id = ?
                order by created_at desc limit 1',
                [$id]
            );
            $domain->lastCheck = (empty($lastCheck)) ? null : $lastCheck[0]->status_code;
            return $domain;
        }, $table);
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
