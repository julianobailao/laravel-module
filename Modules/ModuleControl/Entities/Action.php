<?php

namespace Modules\ModuleControl\Entities;

use Modules\ModuleControl\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use Uuids;

    public $incrementing = false;

    protected $fillable = [
        'title', 'description'
    ];

    public function scopeSearch($query, $term)
    {
        if ($term) {
            $query->where('title', 'like', sprintf('%%s%', $term));
        }

        return $query;
    }
}
