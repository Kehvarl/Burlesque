<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function users()
    {
      return $this->belongsToMany(User::class)->withPivot('id', 'display_name', 'message_font', 'message_color');
    }

    public function posts()
    {
      return $this->hasMany(ChatPost::class);
    }
}
