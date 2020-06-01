<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Domain;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;

class MainPageTest extends TestCase
{
    use DatabaseMigrations;

    public function testMainPageAviliable()
    {
        $response = $this->get(route('main.index'));
        $response->assertStatus(200);
    }

    public function testMainPageAction()
    {
        $domain = factory(Domain::class)->make();
        $data = Arr::only($domain->toArray(), ['name']);
        $parsedName = parse_url($data['name']);
        $data['name'] = "{$parsedName['scheme']}://{$parsedName['host']}";
        $response = $this->post(route('store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('domains', $data);
    }

    public function testMainPageActionWithError()
    {
        $data['name'] = "1234";
        $response = $this->post(route('store'), $data);
        $response->assertSessionHasErrors();
        $response->assertStatus(302);
    }
}
