<?php

namespace App\Jobs;

use DiDom\Document;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        $check = DB::table('domain_checks')->find($this->checkId);

        $domain = DB::table('domains')->find($check->domain_id);

        $domenName = $domain->name;
        $latestCheck = $check->created_at;
        Log::info($domenName);
        
        try {
            $response = Http::get($domenName);
            $status = $response->status();
            $html = $response->body();
            $document = new Document($html);
            
            $headlineH1 = $document->has('h1') ? $document->find('h1')[0]->text() : '';

            $keywords = $document->has("meta[name=keywords]")
                ? $document->find("meta[name=keywords]::attr(content)")[0]
                : '';
            
            $description = $document->has("meta[name=description]")
                ? $document->find("meta[name=description]::attr(content)")[0]
                : '';

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
            ->update(['updated_at' => $latestCheck]);
        
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
