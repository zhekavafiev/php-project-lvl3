<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Src\Seo\SeoHelper as SeoHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetSEO implements ShouldQueue
{
    use Dispatchable;

    private $checkId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($checkId)
    {
        $this->checkId = $checkId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $domain = DB::table('domains')
            ->join('domain_checks', 'domain_checks.domain_id', '=', 'domains.id')
            ->select(
                'domains.id as id',
                'name',
                'domain_checks.created_at as last_check'
            )
            ->where('domain_checks.id', $this->checkId)
            ->get()->first();

        $domenName = $domain->name;
        $lastCheck = $domain->last_check;
        
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
        
        DB::table('domains')
            ->where('id', $domain->id)
            ->update(['updated_at' => $lastCheck]);
        
        DB::table('domain_checks')
            ->where('id', $this->checkId)
            ->update([
                'h1' => $headlineH1,
                'keywords' => $keywords,
                'description' => $description,
                'status_code' => $status
            ]);
    }
}
