<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DomainCheckTest extends TestCase
{
    use DatabaseMigrations;

    private $name;
    private $id;

    protected function setUp(): void
    {
        parent::setUp();
        $domain['name'] = 'http://example.com';
        $this->name = $domain['name'];
        $this->id = DB::table('domains')->insert($domain);
    }

    public function testAddCheck()
    {
        $html = file_get_contents(realpath(__DIR__ . '/../fixtures/fake.html'));
        
        Http::fake([
            $this->name => Http::response($html, 200)
        ]);
        
        $expected = [
            'domain_id' => $this->id,
            'status_code' => 200,
            'keywords' => 'keywordsTest',
            'h1' => 'h1Test',
            'description' => 'descriptionTest',
        ];

        $response = $this->post(route('check', ['id' => $this->id]));
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('domain_checks', $expected);
    }
}
