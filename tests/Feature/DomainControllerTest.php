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
        $id = $domain->id;
        $domain->save();
        $response = $this->get(route('domain', ['id' => $id]));
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    public function testDomainPageNotHasOnDB()
    {
        $id = rand(0, 100);
        $response = $this->get(route('domain', ['id' => $id]));
        $response->assertStatus(404);
    }
}
