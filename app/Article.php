<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = ['id'];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }
}
