<?php

namespace Modules\ModuleControl\Entities;

use Illuminate\Database\Eloquent\Model;

class ActionRule extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'module_name', 'route_uri', 'route_method'
    ];
}
