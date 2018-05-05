<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Tag;
use App\Category;
use Validator;

use App;
use DB;
use PDF;


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
        $post = Post::paginate(3);
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
        $tag = Tag::all()->pluck('title','id');
        $category = Category::all()->pluck('title','id');
        $related_post = Post::all()->pluck('title','id');

        return view('add_post', compact('category', 'tag', 'related_post') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //dd( $request->all() );

        $messages = [
                    'required' => 'The :attribute field is required.',
                    'same'    => 'The :attribute and :other must match.',
                    'size'    => 'The :attribute must be exactly :size.',
                    'between' => 'The :attribute value :input is not between :min - :max.',
                    'in'      => 'The :attribute must be one of the following types: :values',
                ];

        $validator = Validator::make( $request->all() ,
                [
                    'title' => 'required|max:100',
                    'slug' => 'required|unique:posts,slug',
                    'category' => 'required|integer',
                    'tags' => 'required',
                    'tags.*' => 'required',
                    'related_post' => 'required',
                    'related_post.*' => 'required',
                    'files' => 'required',
                    'files.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'meta_description' => 'required|max:500'
                ],$messages
            );

       

        if ( $validator->fails() ) {

            //$errors = $validator->errors(); dd( $errors );
            /*
            if ($errors->has('email')) {
            foreach ($errors->get('email') as $message) {
                //
            }}*/

            return redirect('post/create')
                        ->withErrors($validator, 'post_form')
                        ->withInput();
        

        } else {

            $post = new Post;
            $post->title = $request->title;
            $post->slug = $request->slug;
            $post->category_id = $request->category;
            $post->related_post = json_encode($request->related_post);
            $post->meta_description = $request->meta_description;

            if( $request->hasfile('files') ) {
                $post_files = [];
                foreach( $request->file('files') as $file ) {
                    
                    $fileName = $file->getClientOriginalName();
                    $fileName = time(). '_' . str_replace(' ', '', str_replace("'", "", $fileName ));

                    $destination = public_path().'/uploads/';
                    $file->move( $destination , $fileName );
                    array_push( $post_files , $fileName );
                }
                $post->files = json_encode( $post_files );
            }

            $post->save();
            $post->tags()->sync($request->tags, false);            
            return back()->with('status','Successfully Post Created..');

        }
    }


    public function store1(Request $request)
    {
        //dd( $request->all() );

        /*
            dd($request->get('files'));
            $image = $request->file('files');
            $image->move();
        */


        if($request->hasfile('filename'))
         {
            foreach($request->file('filename') as $image)
            {
                $name=$image->getClientOriginalName();
                $image->move(public_path().'/images/', $name);  
                $data[] = $name;  
            }
         }


        Post::create([
            'title' => $request->title,
            'slug' => rand(1,100),
            'category_id' => $request->category,
            'related_post' => collect($request->related_post)->toJson() ,
            'meta_desciption' => $request->description ,
            'files' => $request->get('files')
        ]);
            
        // $post->tags->sync($request->tags);
        // return redirect('post')->with('status','Successfully Post Created..');
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
        #$post->categories;
        #$post->tags;
        #dd( $post->load('categories')->load('tags')->toArray() );

        return view('show_post', compact('post') );   
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
        $tag = Tag::all()->pluck('title','id');
        $category = Category::all()->pluck('title','id');
        $related_post = Post::all()->pluck('title','id');
        $db_old_tags = $post->tags->pluck('id');
        return view('edit_post', compact('category', 'tag', 'related_post','post', 'db_old_tags') );   
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

        $validator = Validator::make( $request->all() ,
            [
                'title' => 'required|max:100',
                'category' => 'required|integer',
                'tags' => 'required',
                'tags.*' => 'required',
                'related_post' => 'required',
                'related_post.*' => 'required',
                'meta_description' => 'required|max:500'
            ]
        );


        if( $validator->fails() ) {
            //dd( $validator->messages()->toArray() );
            return redirect()->route('post.edit',$post->id)
                        ->withErrors($validator, 'post_form')
                            ->withInput();

        }else{

            $post->title = $request->title;
            $post->category_id = $request->category;
            $post->meta_description = $request->meta_description;

            if( $request->related_post )
                $post->related_post = json_encode($request->related_post);
            else
                $post->related_post = '[]';
            
            if( $request->hasfile('files') ) {
                $post_files = [];
                foreach($request->file('files') as $file ) {
                    $fileName = $file->getClientOriginalName();
                    $fileName = time().'_'.str_replace(' ','', str_replace("'","",$fileName ) );
                    $destination = public_path().'/uploads/';
                    $file->move($destination, $fileName );
                    array_push($post_files, $fileName);
                }
                if(!empty($post_files)) {
                    $post->files = json_encode($post_files);        
                }
            }
                
            if($request->tags)
                $post->tags()->sync($request->tags,false);
            else
                $post->tags->sync([],false);

            $post->save();
        
            return redirect('/post')->with('status','Successfully Updated...');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {   
        //
        $post->tags()->detach();
        $post->delete();
        return redirect('post')->with('status','Successfully Deleted');
    }

    public function pdf_stream() {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Test</h1>');
        return $pdf->stream();
    }

    
    public function pdf_html() {
        // PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        // PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->save('myfile.pdf')

        return PDF::loadFile(public_path().'/myfile.html')->save('/path-to/my_stored_file.pdf')->stream('download.pdf');

    }

    public function pdfview(Request $request)
    {
        $users = DB::table("users")->get();
        view()->share('users',$users);

        if($request->has('download') ){
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            $pdf = PDF::loadView('pdfview');
            return $pdf->download('pdfview.pdf');
        }
        return view('pdfview');
    }

}
