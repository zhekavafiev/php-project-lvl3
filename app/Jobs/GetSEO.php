<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Src\Seo\SeoHelper as SeoHelper;
use Illuminate\Support\Facades\DB;

class GetSEO implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $check;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($check)
    {
        $this->check = $check;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $domenName = DB::table('domains')
            ->select('name')
            ->where('id', $this->check->domain_id)
            ->get()->toArray()[0]->name;
        try {
            $response = Http::get($domenName);
            $status = $response->status();
            $document = new Document($response->body());
            $seo = new SeoHelper($document);
            $headlineH1 = $seo->getHeadline('h1');
            $keywords = $seo->getMetaContent('keywords');
            $description = $seo->getMetaContent('description');
        } catch (\Exception $e) { // ловит несуществующие вдреса
            $status = 500;
            $headlineH1 = '';
            $keywords = '';
            $description = '';
        }

        $lastcheck = $this->check->created_at;
        
        DB::table('domains')
            ->where('id', $this->check->domain_id)
            ->update(['updated_at' => $lastcheck]);
        
        DB::table('domain_checks')
            ->where('id', $this->check->id)
            ->update([
                'h1' => $headlineH1,
                'keywords' => $keywords,
                'description' => $description,
                'status_code' => $status
            ]);
    }
}
