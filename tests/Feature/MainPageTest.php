<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MainPageTest extends TestCase
{
    use DatabaseMigrations;

    public function testMainPageAviliable()
    {
        $response = $this->get(route('index'));
        $response->assertStatus(200);
    }

    public function testMainPageActionWithError()
    {
        $data['name'] = "1234";
        $response = $this->post(route('domains.store'), $data);
        $response->assertSessionHasErrors();
        $response->assertStatus(302);
    }
}
