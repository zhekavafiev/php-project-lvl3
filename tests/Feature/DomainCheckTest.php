<?php

namespace Tests\Feature;

use Tests\TestCase;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DomainCheckTest extends TestCase
{
    private $domainForTest;

    protected function setUp(): void
    {
        parent::setUp();
        $name = Factory::create()->url;
        $parsedName = parse_url($name);
        $name = "{$parsedName['scheme']}://{$parsedName['host']}";
        DB::insert('insert into domains (name) values (?)', [$name]);
        
        $this->domainForTest =  DB::table('domains')
            ->select('*')
            ->limit(1)
            ->get()->toArray()[0];
    }

    public function testAddCheck()
    {
        $statusCode = rand(300, 500);
        $h1 = 'h1';
        $keywords = 'keywords';
        $description = 'description';
        
        Http::fake([
            $this->domainForTest->name => Http::response(
                "<h1>{$h1}</h1>" .
                "<meta name=\"keywords\" content=\"{$keywords}\">" .
                "<meta name=\"description\" content=\"{$description}\">",
                $statusCode
            )
        ]);

        $response = $this->post(route('check', ['id' => $this->domainForTest->id]));
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('domain_checks', [
            'domain_id' => $this->domainForTest->id,
            'status_code' => $statusCode,
            'keywords' => $keywords,
            'h1' => $h1,
            'description' => $description,
            ]);
    }
}
