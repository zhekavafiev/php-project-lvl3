<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Domain;
use mysqli;

class DomainController extends Controller
{
    public function index()
    {
        return view('main.index');
    }

    public function save(Request $request)
    {
        $validator = $request->validate([
            'domain.name' => 'url'
        ]);
        $name = $request->input('domain.name');
        $domain = new Domain();
        try {
            $domain->name = $name;
            $domain->save();
            session()->flash('message', 'Domain has added');
            return redirect()->action('DomainController@show');
        } catch (\Exception $error) {
            $query = DB::select('Select id from domains where name = ?', [$name]);
            $id = $query[0]->id;
            session()->flash('message', "Domen {$name} has been checked early");
            return redirect()->route('domain', ['id' => $id]);
        }
    }

    public function view($id)
    {
        $domain = DB::select('select * from domains where id = ?', [$id]);
        if (empty($domain)) {
            return abort(404);
        }
        return view('domain.domain', ['table' => $domain]);
    }

    public function show()
    {
        $table = DB::table('domains')->get();
        return view('domains.domains', ['table' => $table]);
    }

    public function check($id, Request $request)
    {
        var_dump($request);
        return redirect()->route('domains');
    }
}
