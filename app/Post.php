<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'description', 'image'];

    public function comment()
    {
        return $this->hasMany('App\Comment', 'post_id');
    }

    public function image()
    {
        return $this->hasMany('App\Image', 'post_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
