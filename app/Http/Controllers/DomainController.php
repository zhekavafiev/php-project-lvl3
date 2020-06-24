<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DomainController extends Controller
{
    public function show($id, Request $request)
    {
        $page = empty($request['page']) ? 1 : $request['page'];
        
        $countChecks = DB::select('select count(*) as count from domain_checks
            where domain_id = ?', [$id])[0]->count;
        $perPage = 5;

        if (!is_numeric($page) || ((ceil($countChecks / $perPage) < $page) && $countChecks != 0)) {
            return back()->with('errors', 'You request is wrong');
        }

        $offset = ($page - 1) * $perPage;

        $domain = DB::select('select domains.id, name, domains.created_at,
            max(domain_checks.created_at) as last_check, h1, keywords, description, status_code
            from domains left join domain_checks
            on domains.id = domain_checks.domain_id
            group by name
            having domains.id = ?', [$id]);
        
        if (empty($domain)) {
            return abort(404);
        }

        $checksOnPage = DB::select('select id, created_at, updated_at, status_code, h1, keywords, description
            from domain_checks 
            where domain_id = ?
            order by created_at desc
            limit ?
            offset ?', [$id, $perPage, $offset]);

        $checks = new Paginator($checksOnPage, $countChecks, $perPage, $page, [
            'path' => (route('domains.show', $id))
        ]);
        
        return view('domain.show', compact('domain', 'checks'));
    }

    public function index(Request $request)
    {
        $page = empty($request['page']) ? 1 : $request['page'];
        $countDomain = DB::select('select count(*) as count from domains')[0]->count;
        $perPage = 10;

        if (!is_numeric($page) || ((ceil($countDomain / $perPage) < $page) && $countDomain != 0)) {
            return back()->with('errors', 'You request is wrong');
        }

        $offset = ($page - 1) * $perPage;

        $domainsOnPage = DB::select('select domains.id, domains.name, domains.created_at, 
            max(domain_checks.created_at) as last_check, domain_checks.status_code
            from domains left join domain_checks
            on domains.id = domain_checks.domain_id
            group by name
            order by domains.id
            limit ?
            offset ?', [$perPage, $offset]);

        $domains = new Paginator($domainsOnPage, $countDomain, $perPage, $page, [
            'path' => (route('domains.index'))
        ]);
        return view('domain.index', compact('domains'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'url'
        ]);

        $parsedName = parse_url($request->input('name'));
        $name = "{$parsedName['scheme']}://{$parsedName['host']}";
        try {
            $query = DB::select('Select id from domains where name = ?', [$name]);
            $id = $query[0]->id;
            session()->flash('errors', "Domen {$name} has been checked early");
            return redirect()->route('domains.show', ['id' => $id]);
        } catch (\Exception $error) {
            $date = Carbon::now();
            DB::insert('insert into domains (name, created_at) values (?, ?)', [$name, $date]);
            session()->flash('message', "Domain {$name} has added");
            $query = DB::select('Select id from domains where name = ?', [$name]);
            $id = $query[0]->id;
            return redirect()->route('domains.show', $id);
        }
    }
}
