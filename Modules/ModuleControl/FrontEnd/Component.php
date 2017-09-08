<?php

namespace Modules\ModuleControl\FrontEnd;

class Component
{
    protected $name;
    protected $title;
    protected $props;

    public function __construct($name, $title)
    {
        $this->name = $name;
        $this->title = $title;
        $this->props = [];
    }

    public function __call($key, $args)
    {
        $this->props[$key] = count($args) > 1 ? $args : $args[0];

        return $this;
    }

    public function __get($key)
    {
        if (isset($this->props[$key])) {
            return $this->props[$key];
        }

        return null;
    }

    public function getData()
    {
        return get_object_vars($this);
    }
}
