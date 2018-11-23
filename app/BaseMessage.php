<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

abstract class BaseMessage extends Model
{
    public $fillable = ['content', 'creator_id', 'edited', 'deleted', 'is_link', 'link_info', 'is_link_to_image'];

    protected $attributes = array(
        'edited' => false,
        'deleted' => false,
    );

    protected $casts = [
        'link_info' => 'array',
    ];


    public function creator()
    {
        return $this->belongsTo('App\User');
    }

    public function getContentAttribute($content)
    {
        return ($this->attributes['deleted']) ? 'message supprimé' : $content;
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
   protected $hidden = ['creator', 'updated_at'];
}
