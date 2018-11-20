<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $fillable = ['content', 'creator_id', 'group_id'];


    public function creator()
    {
        return $this->belongsTo('App\User');
    }

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    public function getCreatorNameAttribute()
    {
        return $this->creator->name;
    }

    public function getTimeAttribute()
    {
        return $this->created_at->format("H:i:s");
    }

    public function getDateAttribute()
    {
        return $this->created_at->format("d-m-Y");
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
   protected $appends = ['creator_name', 'time', 'date'];
   protected $hidden = ['creator', 'updated_at', 'group_id'];
}
