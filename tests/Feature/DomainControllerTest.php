<?php

namespace Tests\Feature;

use App\Domain;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DomainControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testPageAviliable()
    {
        $response = $this->get(route('domains.index'));
        $response->assertStatus(200);
    }

    public function testDomainPageHasOnDB()
    {
        $domain = factory(Domain::class)->make();
        $domain->save();
        $response = $this->get(route('domains.show', $domain));
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    public function testIndexWrongPaginatePage()
    {
        $domain = factory(Domain::class)->make();
        $domain->save();
        $response = $this->get(route('domains.index', ['page' => 100]));
        $response->assertStatus(302);
        $response->assertSessionHas('errors');
    }

    public function testDomainPageNotHasOnDB()
    {
        $id = rand(0, 100);
        $response = $this->get(route('domains.show', ['id' => $id]));
        $response->assertStatus(404);
    }
}
