<?php

namespace Modules\ModuleControl\Entities;

use Modules\ModuleControl\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class ActionRoute extends Model
{
    use Uuids;

    public $incrementing = false;

    protected $fillable = [];
}
