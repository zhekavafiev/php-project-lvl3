<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Domain;
use Illuminate\Support\Arr;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DomainControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        factory(Domain::class, 2)->make();
    }

    public function testIndex()
    {
        $response = $this->get(route('index'));
        $response->assertStatus(200);
    }

    public function testDomains()
    {
        $response = $this->get(route('domains'));
        $response->assertStatus(200);
    }

    public function testDomain()
    {
        $response = $this->get(route('domain', ['id' => 5]));
        $response->assertStatus(404);
    }
    
    public function testSave()
    {
        $factoryData = factory(Domain::class)->make()->toArray();
        $data = Arr::only($factoryData, ['name']);
        $response = $this->post(route('save'), $data);
        $response->assertSessionHasNoErrors();
        // $response->assertRedirect();

        $this->assertDatabaseHas('domains', $data);
    }
}
