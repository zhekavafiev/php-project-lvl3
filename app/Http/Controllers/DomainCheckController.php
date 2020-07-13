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
        $checkId = DB::table('domain_checks')
            ->insertGetId([
                'domain_id' => $id,
                'created_at' => $date
            ]);
            
        GetSEO::dispatchAfterResponse($checkId);

        session()->flash(
            'message',
            "Your request is being processed. 
            If data is not selected, try refreshing the page later"
        );

        return redirect()->route('domains.show', ['id' => $id]);
    }
}
