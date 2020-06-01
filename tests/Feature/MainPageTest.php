<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Domain;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\DatabaseRule;

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

}
