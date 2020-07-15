<?php

namespace Tests\Feature;

use Tests\TestCase;

class MainPageTest extends TestCase
{
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
