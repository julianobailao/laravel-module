<?php

namespace Modules\ModuleControl\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\ModuleControl\Traits\Uuids;

class ActionGroup extends Model
{
    use Uuids;

    public $incrementing = false;

    protected $fillable = [
        'title', 'description'
    ];

    protected $hidden = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];
}
