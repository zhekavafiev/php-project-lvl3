<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class DomainControllerTest extends TestCase
{
    protected $id;

    protected function setUp(): void
    {
        parent::setUp();
        $domain['name'] = 'http://example.com';
        $this->id = DB::table('domains')->insertGetId($domain);
        
        $check = [
            'domain_id' => $this->id,
            'created_at' => Carbon::now(),
            'status_code' => 200
        ];
        DB::table('domain_checks')->insert($check);
    }

    public function testDomainStore()
    {
        $faker = Factory::create();
        $data['name'] = $faker->url;
        $parsedName = parse_url($data['name']);
        $data['name'] = "{$parsedName['scheme']}://{$parsedName['host']}";
        $response = $this->post(route('domains.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('domains', $data);
    }

    public function testDomainShow()
    {
        $response = $this->get(route('domains.show', ['id' => $this->id]));
        $response->assertStatus(200);
    }

    public function testIndexPage()
    {
        $response = $this->get(route('domains.index'));
        $response ->assertStatus(200);
    }
}
