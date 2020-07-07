<?php

namespace Tests\Feature;

use Tests\TestCase;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MainPageTest extends TestCase
{
    use DatabaseMigrations;

    public function testMainPageAviliable()
    {
        $response = $this->get(route('index'));
        $response->assertStatus(200);
    }

    public function testMainPageAction()
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

    public function testMainPageActionWithError()
    {
        $data['name'] = "1234";
        $response = $this->post(route('domains.store'), $data);
        $response->assertSessionHasErrors();
        $response->assertStatus(302);
    }
}
