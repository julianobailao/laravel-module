<?php

namespace Modules\ModuleControl\Tests;

use Tests\TestCase;
use Modules\ModuleControl\Traits\ModuleDatabaseMigrations;

class ActionControllerTest extends TestCase
{
    use ModuleDatabaseMigrations;

    public function testReturnActionList()
    {
        $data = factory(\Modules\ModuleControl\Entities\Action::class, 10)->create();
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
}
