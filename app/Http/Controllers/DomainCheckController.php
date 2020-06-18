<?php

namespace App\Http\Controllers;

use App\DomainCheck;
use App\Jobs\GetSEO;

class DomainCheckController extends Controller
{
    public function check($id)
    {
        $check = new DomainCheck();
        $check->domain_id = $id;
        $check->save();

        GetSEO::dispatchAfterResponse($check);

        session()->flash(
            'message',
            "Your request is being processed. 
            If data is not selected, try refreshing the page later"
        );

        return redirect()->route('domains.show', ['id' => $id]);
    }
}
