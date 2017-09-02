<?php

namespace Modules\Testing\Presets;

class ResourceIndex extends Preset
{
    public function runTest()
    {
        $data = $this->getFactory();
        $response = $this->get($this->getRoute());

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => array_keys($data->first()->toArray()),
                ],
            ]);
    }
}
