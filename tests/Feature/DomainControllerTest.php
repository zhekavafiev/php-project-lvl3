<?php

namespace Tests\Feature;

use App\Domain;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;

class DomainControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testDomainShow()
    {
        $domain = factory(Domain::class)->make();
        $domain->save();
        $response = $this->get(route('domains.show', $domain));
        $response->assertSeeInOrder([$domain->name, $domain->created_at]);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    public function testIndexWrongPaginatePage()
    {
        $domain = factory(Domain::class)->make();
        $domain->save();
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

    public function testIndexContent()
    {
        $domain = factory(Domain::class)->make();
        $data = Arr::only($domain->toArray(), ['name']);
        $parsedName = parse_url($data['name']);
        $data['name'] = "{$parsedName['scheme']}://{$parsedName['host']}";
        $this->post(route('domains.store'), $data);

        $response = $this->get(route('domains.index'));
        $response->assertSeeInOrder([$domain->index, $data['name'], $domain->created_at]);
        $response->assertStatus(200);
    }
}
