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

class DomainControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testMainPageAviliable()
    {
        $response = $this->get(route('domains.index'));
        $response->assertStatus(200);
    }
}
