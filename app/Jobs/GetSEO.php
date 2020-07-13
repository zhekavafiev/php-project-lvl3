<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Src\Seo\SeoHelper as SeoHelper;
use Illuminate\Support\Facades\DB;

class GetSEO implements ShouldQueue
{
    use Dispatchable;

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
        $domain = DB::table('domains')
            ->where('id', $this->check->domain_id)
            ->get()->first();
        $domenName = $domain->name;
        try {
            $response = Http::get($domenName);
            $status = $response->status();
            $html = $response->body();
            $seo = new SeoHelper($html);
            $headlineH1 = $seo->getHeadline('h1');
            $keywords = $seo->getMetaContent('keywords');
            $description = $seo->getMetaContent('description');
        } catch (\Exception $e) {
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
