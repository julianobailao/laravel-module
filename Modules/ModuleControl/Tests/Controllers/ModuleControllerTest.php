<?php

namespace Modules\ModuleControl\Tests\Controllers;

use Tests\TestCase;

class ModuleControllerTest extends TestCase
{
    public function testShouldReturnModuleList()
    {
        $response = $this->get('/api/modules');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'alias',
                    'name',
                    'active',
                ],
            ]);
    }

    public function testShouldReturnSpecifiedModuleByPath()
    {
        $response = $this->get('/api/modules/ModuleControl');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'alias',
                'name',
                'active',
                'routes' => [
                    '*' => [
                        'uri',
                        'method',
                    ],
                ],
            ]);
    }

    public function testShouldReturn404ErrorWhenInvalidPath()
    {
        $response = $this->get('1nv4l1d/m0dul3/p4th');

        $response->assertStatus(404);
    }
}
