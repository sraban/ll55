<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
	protected $appends = ['postCount'];

	public function posts() {
		#return $this->belongsTo('App\Post');
		return $this->hasMany('App\Post','category_id');
	}

	public function children() 
	{
	    return $this->hasMany('App\Category::class', 'parent_id', 'id');
	}

	public function childrenPosts()
    {
        return $this->hasManyThrough('App\Post::class', 'App\Category::class', 'parent_id');
    }

    public function getPostCountAttribute()
    {
        return $this->posts()->count() + $this->childrenPosts()->count();
    }


/*
Category::first()
Category::find(1)
Category::findMany([1,2])
Category::where('parent_id',1)->get()
Category::where('parent_id',1)->withCount('posts')->get();
Category::where('parent_id', 1)->get()->pluck('posts','title');

#*Category::has('children.posts')->get();

*/


}
