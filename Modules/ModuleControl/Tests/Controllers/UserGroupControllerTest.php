<?php

namespace Modules\ModuleControl\Tests\Controllers;

use Tests\TestCase;
use Modules\ModuleControl\Entities\Action;
use Modules\ModuleControl\Entities\UserGroup;
use Modules\ModuleControl\Traits\ModuleDatabaseMigrations;

class UserGroupControllerTest extends TestCase
{
    use ModuleDatabaseMigrations;

    public function testReturnUserGroupList()
    {
        $data = factory(UserGroup::class, 10)->create();
        $response = $this->get('/api/user-groups');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => array_keys($data->first()->toArray()),
                ],
            ]);
    }

    public function testReturnEspecifiedUserGroupbyUuid()
    {
        $data = factory(UserGroup::class)->create();
        $response = $this->get(sprintf('/api/user-groups/%s', $data->id));

        $response
            ->assertStatus(200)
            ->assertJson($data->toArray());
    }

    public function testCreateNewUserGroupAndReturnUserGroupData()
    {
        $data = factory(UserGroup::class)->make();
        $data->permissions = [
            [
                'action_id' => factory(Action::class)->create()->id,
            ],
        ];
        $response = $this->json('POST', '/api/user-groups', $data->toArray());
        unset($data->permissions);

        $response
            ->assertStatus(201)
            ->assertJsonFragment($data->toArray());
    }

    public function testUpdateUserGroupAndReturnUserGroupData()
    {
        $userGroup = factory(UserGroup::class)->create();
        $data = factory(UserGroup::class)->make();
        $data->permissions = [
            [
                'action_id' => factory(Action::class)->create()->id,
            ],
        ];
        $response = $this->json('PUT', sprintf('/api/user-groups/%s', $userGroup->id), $data->toArray());

        $response
            ->assertStatus(200)
            ->assertJsonFragment(UserGroup::find($userGroup->id)->load('permissions')->toArray());
    }

    public function testDestroyUserGroupAndCheckDatabase()
    {
        $userGroup = factory(UserGroup::class)->create();
        $response = $this->json('DELETE', sprintf('/api/user-groups/%s', $userGroup->id));

        $response->assertStatus(204);
        $this->assertEquals(null, UserGroup::find($userGroup->id));
    }
}
