<?php

namespace Tests\Feature;

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

    public function testDomainPageNotHasOnDB()
    {
        $count = DB::table('domains')->max('id');
        $id = rand($count + 1, 100);
        $response = $this->get(route('domains.show', ['id' => $id]));
        $response->assertStatus(404);
    }

    //add data on DB, add on setup
}
