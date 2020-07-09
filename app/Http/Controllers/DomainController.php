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
        $perPage = 5;
        $countChecks = DB::table('domain_checks')
            ->where('domain_id', $id)
            ->count();

        if (!is_numeric($page) || ((ceil($countChecks / $perPage) < $page) && $countChecks != 0)) {
            return back()->with('errors', 'You request is wrong');
        }

        $offset = ($page - 1) * $perPage;

        $domain = DB::select('select domains.id, domains.name, domains.created_at, 
        domain_checks.created_at as last_check, domain_checks.status_code, domain_checks.h1,
        domain_checks.keywords, domain_checks.description
        from domains left join domain_checks
        on domains.id = domain_checks.domain_id
            and domain_checks.created_at = (SELECT MAX(created_at) FROM domain_checks WHERE domain_id = domains.id)
        where domains.id = ?', [$id]);
        
        // $domain = DB::table('domains')
        //     ->select(
        //         'domains.id',
        //         'domains.created_at',
        //         'domains.name',
        //         'domain_checks.h1',
        //         'domain_checks.keywords',
        //         'domain_checks.description',
        //         'status_code',
        //         DB::raw('max(domain_checks.created_at) as last_check')
        //     )
        //     ->leftJoin('domain_checks', 'domains.id', '=', 'domain_checks.domain_id')
        //     ->groupBy(
        //         'domains.id',
        //         'domains.created_at',
        //         'domain_checks.h1',
        //         'domain_checks.keywords',
        //         'domain_checks.description',
        //         'status_code'
        //     )
        //     ->orderByDesc('domains.id')
        //     ->where('domains.id', $id)
        //     ->get()->toArray()[0];

        if (empty($domain)) {
            return abort(404);
        }

        $checksOnPage = DB::table('domain_checks')
            ->select('id', 'created_at', 'updated_at', 'status_code', 'h1', 'keywords', 'description')
            ->where('domain_id', $id)
            ->orderByDesc('created_at')
            ->limit($perPage)
            ->offset($offset)
            ->get();

        $checks = new Paginator($checksOnPage, $countChecks, $perPage, $page, [
            'path' => (route('domains.show', $id))
        ]);
        
        return view('domain.show', compact('domain', 'checks'));
    }

    public function index(Request $request)
    {
        $page = empty($request['page']) ? 1 : $request['page'];

        $countDomain = DB::table('domains')
            ->count();

        $perPage = 10;

        if (!is_numeric($page) || ((ceil($countDomain / $perPage) < $page) && $countDomain != 0)) {
            return back()->with('errors', 'You request is wrong');
        }

        $offset = ($page - 1) * $perPage;

        // <-- worked version

        // $domainsOnPage = DB::select('select domains.id, domains.name, domains.created_at, 
        //     domain_checks.created_at as last_check, domain_checks.status_code
        //     from domains left join domain_checks
        //     on domains.id = domain_checks.domain_id
        //         and domain_checks.created_at = (SELECT MAX(created_at) FROM domain_checks WHERE domain_id = domains.id)
        //         order by domains.id desc
        //     limit ?
        //     offset ?', [$perPage, $offset]);

        // <-- sansei version with distinkt
        // ь проблемы, я не уверен, что нужную поселднюю запись сохранит при 

        // $domainsOnPage = DB::table('domain_checks')
        //     ->orderByDesc('domain_id')
        //     ->orderByDesc('created_at')
        //     ->get()
        //     ->keyBy('domain_id');

        $domainsOnPage = DB::table('domains')
            ->select(
                'domains.id',
                'domains.created_at',
                'domains.name',
                'status_code',
                DB::raw('max(domain_checks.created_at) as last_check')
            )
            ->leftJoin('domain_checks', 'domains.id', '=', 'domain_checks.domain_id')
            ->groupBy('domains.id', 'status_code', 'domains.created_at')
            ->distinct('domains.id')
            ->orderByDesc('domains.id')
            ->limit($perPage)
            ->offset($offset)
            ->get()->toArray();


        // $domainsOnPage = DB::table('domain_checks')
        //     ->select(
        //         'domain_id',
        //         'status_code',
        //         DB::raw('max(domain_checks.created_at) as last_check')
        //     )
        //     ->groupBy('domain_id', 'status_code')
        //     ->distinct('domain_id')
        //     ->get()->toArray();
            dd($domainsOnPage);
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
