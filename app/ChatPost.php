<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatPost extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function room()
    {
      return $this->belongsTo(Room::class);
    }
}
