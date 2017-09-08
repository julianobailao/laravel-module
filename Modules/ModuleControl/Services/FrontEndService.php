<?php

namespace Modules\ModuleControl\Services;

use Modules\ModuleControl\FrontEnd\ComponentGroup;

class FrontEndService
{
    protected $title;
    protected $subtitle;
    protected $fields;

    public function create($title, $subtitle, callable $callback)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $callback($this->fields = new ComponentGroup());

        return $this;
    }

    public function getData()
    {
        $data = $this->fields->getData();
        $this->fields = [];

        foreach ($data as $field) {
            $this->fields[] = $field;
        }

        return get_object_vars($this);
    }
}
