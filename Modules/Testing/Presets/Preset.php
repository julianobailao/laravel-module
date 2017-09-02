<?php

namespace Modules\Testing\Presets;

use Tests\TestCase;

abstract class Preset extends TestCase
{
    private $factory;
    private $route;

    public function __construct(Factory $factory, $route)
    {
        $this->factory = $factory;
        $this->route = $route;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getFactory()
    {
        return $this->factory;
    }

    abstract public function runTest();
}
