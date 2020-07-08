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
        DB::table('domain_checks')
            ->insert([
                'domain_id' => $id,
                'created_at' => $date
            ]);
            
        $check = DB::table('domain_checks')
            ->select('*')
            ->where('domain_id', $id)
            ->orderByDesc('id')
            ->limit(1)
            ->get();

        GetSEO::dispatchAfterResponse($check[0]);

        session()->flash(
            'message',
            "Your request is being processed. 
            If data is not selected, try refreshing the page later"
        );

        return redirect()->route('domains.show', ['id' => $id]);
    }
}
