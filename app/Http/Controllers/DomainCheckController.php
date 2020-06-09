<?php

namespace App\Http\Controllers;

use App\DomainCheck;
use DiDom\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Src\Seo\SeoHelper as SeoHelper;

class DomainCheckController extends Controller
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
            session()->flash(
                'message',
                "Domain {$domenName} will be checked"
            );
        } catch (\Exception $e) { // ловит несуществующие вдреса
            $status = 500;
            $headlineH1 = '';
            $keywords = '';
            $description = '';
            session()->flash(
                'error',
                "Check ended with problems "
            );
        }
        $check->status_code = $status;
        $check->save(); //Разделено потому что разные Джобы выполняли
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
        return redirect()->route('domains.show', ['id' => $id]);
    }
}
