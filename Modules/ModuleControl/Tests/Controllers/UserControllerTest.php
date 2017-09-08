<?php

namespace Modules\ModuleControl\Tests\Controllers;

use Tests\TestCase;
use Modules\ModuleControl\Entities\User;
use Modules\ModuleControl\Entities\UserGroup;
use Modules\ModuleControl\Traits\ModuleDatabaseMigrations;

class UserControllerTest extends TestCase
{
    use ModuleDatabaseMigrations;

    public function testReturnFrontEndTableData()
    {
        $response = $this->get('/api/users/table');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'title',
                'subtitle',
                'fields',
            ]);
    }

    public function testReturnUserList()
    {
        $data = factory(User::class, 10)->create();
        $response = $this->get('/api/users');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => array_keys($data->first()->toArray()),
                ],
            ]);
    }

    public function testReturnEspecifiedUserbyUuid()
    {
        $data = factory(User::class)->create();
        $response = $this->get(sprintf('/api/users/%s', $data->id));

        $response
            ->assertStatus(200)
            ->assertJson($data->toArray());
    }

    public function testCreateNewUserAndReturnUserData()
    {
        $userGroup = factory(UserGroup::class)->create();
        $user = factory(User::class)->make();
        $payload = $user->toArray();
        $payload['user_group_id'] = $userGroup->id;
        $payload['password'] = bcrypt('secret');
        $response = $this->json('POST', '/api/users', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonFragment($user->toArray());
    }

    public function testUpdateUserAndReturnUserData()
    {
        $user = factory(User::class)->create();
        $data = factory(User::class)->make();
        $response = $this->json('PUT', sprintf('/api/users/%s', $user->id), $data->toArray());

        $response
            ->assertStatus(200)
            ->assertJsonFragment(User::find($user->id)->toArray());
    }

    public function testDestroyUserAndCheckDatabase()
    {
        $user = factory(User::class)->create();
        $response = $this->json('DELETE', sprintf('/api/users/%s', $user->id));

        $response->assertStatus(204);
        $this->assertEquals(null, User::find($user->id));
    }
}
