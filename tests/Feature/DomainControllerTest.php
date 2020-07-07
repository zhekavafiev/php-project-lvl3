<?php

namespace Tests\Feature;

use App\Domain;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use Faker\Factor;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class DomainControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testDomainShow()
    {
        $domain['name'] = Factory::create()->url;
        $domain['id'] = 1;
        $parsedName = parse_url($domain['name']);
        $domain['name'] = "{$parsedName['scheme']}://{$parsedName['host']}";
        $this->post(route('domains.store', $domain));

        $response = $this->get(route('domains.show', $domain['id']));
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    public function testIndexWrongPaginatePage()
    {
        $domain['name'] = Factory::create()->url;
        $parsedName = parse_url($domain['name']);
        $domain['name'] = "{$parsedName['scheme']}://{$parsedName['host']}";
        $this->post(route('domains.store', $domain));
        
        $wrongPage = rand(2, 100);
        $response = $this->get(route('domains.index', ['page' => $wrongPage]));
        $response->assertStatus(302);
        $response->assertSessionHas('errors');
    }

    public function testDomainPageNotHasOnDB()
    {
        $id = rand(0, 100);
        $response = $this->get(route('domains.show', ['id' => $id]));
        $response->assertStatus(404);
    }

    public function testIndexPage()
    {
        $response = $this->get(route('domains.index'));
        $response->assertStatus(200);
    }
}
