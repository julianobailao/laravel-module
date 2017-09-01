<?php

namespace Modules\ModuleControl\Tests\Services;

use Tests\TestCase;
use Modules\ModuleControl\Facades\Module;

class ModuleServiceTest extends TestCase
{
    private $service;

    public function testShouldReturnModuleList()
    {
        $data = Module::getModulesData();

        $this->assertEquals('modulecontrol', $data['modulecontrol']->alias);
    }

    public function testShouldReturnModuleDataByPath()
    {
        $data = Module::getModuleByPath('ModuleControl');

        $this->assertEquals('modulecontrol', $data->alias);
        $this->assertTrue(isset($data->routes));
    }

    public function testShouldFalseWhenTryToGetModuleWithInvalidOrInexistentPath()
    {
        $data = Module::getModuleByPath('1nv4l1d/m0dul3/n4m3');

        $this->assertEquals(false, $data);
    }
}
