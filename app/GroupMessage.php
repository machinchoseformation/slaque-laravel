<?php

namespace App;

class GroupMessage extends BaseMessage
{
    public function __construct(array $attributes = [])
    {
        array_push($this->fillable, 'group_id');
        array_push($this->hidden, 'group_id');
        parent::__construct($attributes);
    }

    public function group()
    {
        return $this->belongsTo('App\Group');
    }
}
