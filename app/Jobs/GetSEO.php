<?php

namespace App\Jobs;

use DiDom\Document;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Src\StateMachine\StateMachine;

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
        $sm = new StateMachine();

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
            $document = new Document($html);
            
            if ($document->has('h1')) {
                $headlineH1 = $document->find('h1')[0]->text();
            } else {
                $headlineH1 = '';
            }

            if ($document->has("meta[name=keywords]")) {
                $keywords = $document->find("meta[name=keywords]::attr(content)")[0];
            } else {
                $keywords = '';
            }
            
            if ($document->has("meta[name=description]")) {
                $description = $document->find("meta[name=description]::attr(content)")[0];
            } else {
                $description = '';
            }
            $sm->acceptTransitionByName('finished');
            $state = $sm->getCurrentState()->getName();
        } catch (\Exception $e) {
            $status = 000;
            $headlineH1 = '';
            $keywords = '';
            $description = $e->getMessage();
            $sm->acceptTransitionByName('finished_with_error');
            $state = $sm->getCurrentState()->getName();
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
                'status_code' => $status,
                'state' => $state
            ]);
    }
}
