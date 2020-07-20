<?php

namespace App\Http\Controllers;

use App\Jobs\GetSEO;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DomainCheckController extends Controller
{
    public function store($id)
    {
        $date = Carbon::now();
        $checkId = DB::table('domain_checks')
            ->insertGetId([
                'domain_id' => $id,
                'created_at' => $date
            ]);
        Log::info($checkId);
        GetSEO::dispatchAfterResponse($checkId);

        session()->flash(
            'messages',
            "Your request is being processed by number {$checkId}. 
            If data is not selected, try refreshing the page later"
        );

        return redirect()->route('domains.show', ['id' => $id]);
    }
}
