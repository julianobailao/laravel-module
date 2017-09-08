<?php

namespace Modules\ModuleControl\FrontEnd;

class ComponentGroup
{
    protected $components;

    public function put($name, $title)
    {
        return $this->components[$name] = new Component($name, $title);
    }

    public function getData()
    {
        return collect($this->components)->map(function ($component) {
            return $component->getData();
        });
    }
}
