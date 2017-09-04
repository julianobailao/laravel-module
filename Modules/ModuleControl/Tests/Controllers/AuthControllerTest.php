<?php

namespace Modules\ModuleControl\Tests\Controllers;

use Tests\TestCase;
use Modules\ModuleControl\Entities\User;
use Modules\ModuleControl\Entities\UserGroup;
use Modules\ModuleControl\Traits\ModuleDatabaseMigrations;

class AuthControllerTest extends TestCase
{
    use ModuleDatabaseMigrations;

    public function testLoginWithInvalidCredentials()
    {
        $data = factory(User::class)->make();
        $response = $this->post('/auth', $data->toArray());

        $response->assertStatus(401);
    }

    public function testLoginWithValidCredentials()
    {
        $data = factory(User::class)->create();
        $payload = $data->toArray();
        $payload['password'] = 'secret';
        $response = $this->post('/auth', $payload);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['token']);
    }
}
