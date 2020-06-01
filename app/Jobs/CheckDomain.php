<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\DomainCheck;
use Illuminate\Support\Facades\Artisan;

class CheckDomain implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
        // Artisan::call('queue:work');
        // $query = DB::select('select name from domains where id = ?', [$this->check->domain_id]);
        // try {
        //     $response = Http::get($query[0]->name);
        //     $status = $response->status();
        // } catch (\Exception $e) { // ловит несуществующие вдреса
        //     $status = 500;
        // }
        // $this->check->status_code = $status;
        // $this->check->save();
        // $lastcheck = $this->check->created_at;
        // DB::update('update domains set updated_at = ? where id = ?', [$lastcheck, $this->check->domain_id]);
        // Artisan::call('queue:work'); // не знаю как закрыть процесс после выполненя всей очереди
        // // в данном случае при каждом Чеке будет запускаться новый процесс что быстро приведет к перерасходу памяти
        // Artisan::call('queue:clear');// убивает не только процесс, но всю очередь может зацепить чужие задачи в очереди
    }
}
