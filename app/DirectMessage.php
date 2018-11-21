<?php

namespace App;

class DirectMessage extends BaseMessage
{
    public function __construct(array $attributes = [])
    {
        array_push($this->fillable, 'to_id');
        parent::__construct($attributes);
    }

    public function to()
    {
        return $this->belongsTo('App\User');
    }
}
