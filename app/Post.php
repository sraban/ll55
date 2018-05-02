<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //

    public function tags() {
    	return $this->belongsToMany('App\Tag','post_tag','post_id','tag_id');
    }

    public function categories() {
    	return $this->belongsTo('App\Category','category_id');
    }

}
