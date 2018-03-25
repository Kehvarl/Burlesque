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
      return $this->belongsToMany(User::class);
    }

    public function posts()
    {
      return $this->hasMany(ChatPost::class);
    }
}
