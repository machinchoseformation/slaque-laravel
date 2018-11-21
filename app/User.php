<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public function groupsCreated()
    {
        return $this
            ->hasMany('App\Group', 'creator_id');
    }

    public function groups()
    {
        return $this
            ->belongsToMany('App\Group', 'group_user');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'online', 'last_ping_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'updated_at', 'created_at'
    ];
}
