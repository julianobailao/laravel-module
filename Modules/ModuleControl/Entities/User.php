<?php

namespace Modules\ModuleControl\Entities;

use Modules\ModuleControl\Traits\Uuids;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Uuids, Notifiable;

    public $incrementing = false;

    protected $fillable = [
    	'user_group_id', 'name', 'email', 'password'
    ];

    protected $hidden = [
    	'password', 'user_group_id', 'remember_token'
    ];

    public function scopeSearch($query, $term)
    {
        if ($term) {
        	$query->where(function ($query) use ($term) {
            	$query->where('name', 'like', sprintf('%%s%', $term))
            		->where('email', $term);
            });
        }

        return $query;
    }

    public function group()
    {
        return $this->belongsTo(UserGroup::class);
    }
}
