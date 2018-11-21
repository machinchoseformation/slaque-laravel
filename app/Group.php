<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public $fillable = ['name', 'creator_id', 'participants', 'is_one_on_one', 'other_user_id'];

    public function creator()
    {
        return $this->belongsTo('App\User');
    }

    public function otherUser()
    {
        return $this->belongsTo('App\User');
    }

    public function participants()
    {
        return $this->belongsToMany('App\User', 'group_user')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }
}
