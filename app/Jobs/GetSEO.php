<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DiDom\Document;
use Illuminate\Support\Facades\DB;
use Seo\SeoHelper;

class GetSeo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $check;

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
        $query = DB::select('select name from domains where id = ?', [$this->check->domain_id]);
        $domenName = $query[0]->name;
        $document = new Document($domenName, true);
        $seo = new SeoHelper($document);
        $headlineH1 = $seo->getHeadline('h1');
        $keywords = $seo->getMetaContent('keywords');
        $description = $seo->getMetaContent('description');

        DB::update('update domain_checks set h1 = ? where id = ?', [$headlineH1, $this->check->id]);
        DB::update('update domain_checks set keywords = ? where id = ?', [$keywords, $this->check->id]);
        DB::update('update domain_checks set description = ? where id = ?', [$description, $this->check->id]);
    }
}
