<?php

namespace App\Http\Controllers;

use App\DomainCheck;
// use App\Jobs\CheckDomain;
// use App\Jobs\GetSeo;
use DiDom\Document;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Seo\SeoHelper;

class DomainCheckController extends BaseController
{
    public function check($id)
    {
        $check = new DomainCheck();
        $check->domain_id = $id;
        
        $query = DB::select('select name from domains where id = ?', [$id]);
        $domenName = $query[0]->name;
        try {
            $response = Http::get($domenName);
            $status = $response->status();
            $document = new Document($response->body());
            $seo = new SeoHelper($document);
            $headlineH1 = $seo->getHeadline('h1');
            $keywords = $seo->getMetaContent('keywords');
            $description = $seo->getMetaContent('description');
        } catch (\Exception $e) { // ловит несуществующие вдреса
            $status = 'Error communicated with Server';
            $headlineH1 = '';
            $keywords = '';
            $description = '';
        }
        $check->status_code = $status;
        $check->save();
        $lastcheck = $check->created_at;
        DB::update(
            'update domains
            set updated_at = ?
            where id = ?',
            [$lastcheck, $id]
        );
        DB::update(
            'update domain_checks 
            set h1 = ?, keywords = ?, description = ? 
            where id = ?',
            [
                $headlineH1,
                $keywords,
                $description,
                $check->id
            ]
        );
        session()->flash(
            'message',
            "You request has been placed in handle, please refresh page in a minute "
        );
        return redirect()->route('domain', ['id' => $id]);
    }
}
