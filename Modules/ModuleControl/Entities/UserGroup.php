<?php

namespace Modules\ModuleControl\Entities;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use Uuids;

    public $incrementing = false;

    protected $fillable = [
        'title', 'description'
    ];
}
