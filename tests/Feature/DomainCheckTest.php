<?php

namespace Tests\Feature;

use Tests\TestCase;
use Faker\Generator\Factory as Faker;
use Faker\Factory;
use App\Domain;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DomainCheckTest extends TestCase
{
    use DatabaseMigrations;

    public function testAddCheck()
    {
        // $domain = factory(Domain::class)->make();
        $faker = Factory::create();
        $domain = [
            'name' => $faker->url,
            'id' => 1
        ];
        $parsedName = parse_url($domain['name']);
        $domain['name'] = "{$parsedName['scheme']}://{$parsedName['host']}";
        $this->post(route('domains.store', $domain));
        // dd(DB::select('select * from domains'));
        // $domain->save();
        
        $statusCode = rand(300, 500);
        $h1 = 'h1';
        $keywords = 'keywords';
        $description = 'description';
        
        Http::fake([
            $domain['name'] => Http::response(
                "<h1>{$h1}</h1>" .
                "<meta name=\"keywords\" content=\"{$keywords}\">" .
                "<meta name=\"description\" content=\"{$description}\">",
                $statusCode
            )
        ]);

        $response = $this->post(route('check', ['id' => $domain['id']]));
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('domain_checks', [
            'domain_id' => $domain['id'],
            'status_code' => $statusCode,
            'keywords' => $keywords,
            'h1' => $h1,
            'description' => $description,
            ]);
    }
}
