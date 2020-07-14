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

        // if (!is_numeric($page) || ((ceil($countChecks / $perPage) < $page) && $countChecks != 0)) {
        //     return back()->with('errors', 'You request is wrong');
        // }

        $offset = ($page - 1) * $perPage;

        $domain = DB::table('domains')->find($id) ?? null;

        if (empty($domain)) {
            return abort(404);
        }
            
        $lastCheck = DB::table('domain_checks')
            ->where('domain_id', $id)
            ->orderByDesc('created_at')
            ->limit(1)
            ->get()->first();

        $domain->status_code = $lastCheck->status_code ?? '';
        $domain->h1 = $lastCheck->h1 ?? '';
        $domain->keywords = $lastCheck->keywords ?? '';
        $domain->description = $lastCheck->description ?? '';
        $domain->last_check = $lastCheck->created_at ?? '';

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

        // if (!is_numeric($page) || ((ceil($countDomain / $perPage) < $page) && $countDomain != 0)) {
        //     return back()->with('errors', 'You request is wrong');
        // }

        $offset = ($page - 1) * $perPage;
        
        $domainsOnPage = DB::table('domains')
            ->orderByDesc('id')
            ->limit($perPage)
            ->offset($offset)
            ->get();
        
        $lastChecks = DB::table('domain_checks')
            ->select('domain_id', 'created_at', 'status_code')
            ->whereIn('domain_id', $domainsOnPage->pluck('id'))
            ->orderByDesc('domain_id')
            ->orderByDesc('created_at')
            ->distinct('domain_id')
            ->get()->toArray();
        
        $checks = [];
        
        foreach ($lastChecks as $lastCheck) {
            $checks[$lastCheck->domain_id] = $lastCheck;
        }

        $domains = new Paginator($domainsOnPage, $countDomain, $perPage, $page, [
            'path' => (route('domains.index'))
        ]);

        return view('domain.index', compact('domains', 'checks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'url'
        ]);
        $parsedName = parse_url($request->input('name'));
        $name = "{$parsedName['scheme']}://{$parsedName['host']}";
        try {
            $query = DB::table('domains')
                ->select('id')
                ->where('name', $name)
                ->get()->first();
            $id = $query->id;
            $error = ["Domen {$name} has been checked early"];
            session()->flash('errors', collect($error));
            return redirect()->route('domains.show', ['id' => $id]);
        } catch (\Exception $error) {
            $date = Carbon::now();
            DB::insert('insert into domains (name, created_at) values (?, ?)', [$name, $date]);
            $message = "Domain {$name} has added";
            session()->flash('messages', $message);
            $query = DB::select('Select id from domains where name = ?', [$name]);
            $id = $query[0]->id;
            return redirect()->route('domains.show', $id);
        }
    }
}
