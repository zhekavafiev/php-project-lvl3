<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

class MainPageController extends BaseController
{
    public function index()
    {
        return view('main.index');
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
            $domain->name = $name;
            $domain->save();
            session()->flash('message', 'Domain has added');
            return redirect()->action('DomainController@index');
        } catch (\Exception $error) {
            $query = DB::select('Select id from domains where name = ?', [$name]);
            $id = $query[0]->id;
            session()->flash('message', "Domen {$name} has been checked early");
            return redirect()->route('domain', ['id' => $id]);
        }
    }
}