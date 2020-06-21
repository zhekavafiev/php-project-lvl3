<?php

namespace App\Http\Controllers;

use App\Jobs\GetSEO;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DomainCheckController extends Controller
{
    public function check($id)
    {
        $date = Carbon::now();
        DB::insert('insert into domain_checks (domain_id, created_at) values (?, ?)', [$id, $date]);
        $check = DB::select(
            'select * from domain_checks 
            order by id desc
            limit 1'
        );
        
        GetSEO::dispatchAfterResponse($check[0]);

        session()->flash(
            'message',
            "Your request is being processed. 
            If data is not selected, try refreshing the page later"
        );

        return redirect()->route('domains.show', ['id' => $id]);
    }
}
