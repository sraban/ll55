<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
	protected $fillable = ['title','slug', 'category_id', 'meta_description', 'related_post', 'files' ];

	public function categories() {
    	return $this->belongsTo('App\Category','category_id');
    }

    public function tags() {
    	return $this->belongsToMany('App\Tag','post_tag','post_id','tag_id');
    }

/*
Post::with('tags')->where('id',1)->get()
Post::findMany([1,2])->pluck('title')->toArray(); // from column
Post::findMany([1,2])->pluck('categories')->toArray(); // from relation
Post::findMany([1,2])->pluck('categories','slug')->toArray(); // from relation, column(column->relation)
Post::findMany([1,2])->pluck('categories')->pluck('title')->toArray(); // from relation_relation

Post::find(1)->pluck('categories')->pluck('title')->toArray(); // error
Post::where('id',1)->get()->pluck('categories')->pluck('title')->toArray(); //correct

Post::all()->random(3);

Post::find(1)->load('tags')->load('categories')

*/
}
