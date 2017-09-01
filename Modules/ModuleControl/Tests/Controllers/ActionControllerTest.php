<?php

namespace Modules\ModuleControl\Tests;

use Tests\TestCase;
use Modules\ModuleControl\Entities\Action;
use Modules\ModuleControl\Traits\ModuleDatabaseMigrations;

class ActionControllerTest extends TestCase
{
    use ModuleDatabaseMigrations;

    public function testReturnActionList()
    {
        $data = factory(Action::class, 10)->create();
        $response = $this->get('/modules/actions');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                    ],
                ],
            ]);
    }

    public function testReturnEspecifiedActionbyUuid()
    {
        $data = factory(Action::class)->create();
        $response = $this->get(sprintf('/modules/actions/%s', $data->id));

        $response
            ->assertStatus(200)
            ->assertJson($data->toArray());
    }

    public function testCreateNewActionAndReturnActionData()
    {
        $data = factory(Action::class)->make();
        $response = $this->json('POST', '/modules/actions', $data->toArray());

        $response
            ->assertStatus(200)
            ->assertJson($data->toArray());
    }

    public function testUpdateActionAndReturnActionData()
    {
        $action = factory(Action::class)->create();
        $data = factory(Action::class)->make();
        $response = $this->json('PUT', sprintf('/modules/actions/%s', $action->id), $data->toArray());

        $response
            ->assertStatus(200)
            ->assertJson($data->toArray());
    }

    public function testDestroyActionAndCheckDatabase()
    {
        $action = factory(Action::class)->create();
        $response = $this->json('DELETE', sprintf('/modules/actions/%s', $action->id));

        $response->assertStatus(204);
        $this->assertEquals(null, Action::find($action->id));
    }
}
