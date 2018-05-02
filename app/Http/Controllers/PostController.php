<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = 'Sraban';
        $tag = 'tag';
        //$post = Post::with('tags')->where('id',1)->get();
        //$post = Post::findMany([1,2])->pluck('title')->toArray(); // from column
        $post = Post::findMany([1,2])->pluck('categories')->toArray(); // from relation
        $post = Post::findMany([1,2])->pluck('categories','slug')->toArray(); // from relation, column(column->relation)
        $post = Post::findMany([1,2])->pluck('categories')->pluck('title')->toArray(); // from relation_relation
        //$post = Post::find(1)->pluck('categories')->pluck('title')->toArray(); // error
        #Post::where('id',1)->get()->pluck('categories')->pluck('title')->toArray(); //correct
        //dd( $post );
        $post = Post::all();
        #echo '<pre>'; print_r($post);exit;


        return view('view_posts', compact('category', 'tag','post') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $tag = Tag::all();
        $category = Category::all();
        return view('add_post', compact('category', 'tag') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        Post::create();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
        $tag = Tag::all();
        $category = Category::all();
        return view('show_post', compact('category', 'tag', 'post' ) );   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
        $tag = Tag::all();
        $category = Category::all();
        return view('edit_post', compact('category', 'tag', 'post' ) );   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
        $post->title = $request->title;
        $post->tags()->sync();
        $post->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {   //
        $post->tags()->detach();
        $post->delete();
    }
}
