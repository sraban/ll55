@extends('layouts.app')

@section('content')
<div class="w-screen mt-50">
    <div class="row">
        <div class="col-md-12 col-md-offset-2-srabn">
            <div class="panel panel-default">
                <div class="panel-heading">
                All Posts

                <a class="btn" href="{{url('post/create')}}"> Add Post </a>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    


                    <table class="table bordered">
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Meta Description</th>
                        <th>Files</th>
                        <th>Category</th>
                        <th>Tags</th>
                        <th>Related Post</th>
                        <th>Created At</th>
                        <th colspan="2">Action</th>
                      </tr>

                      {{-- dd($post) --}}
                      {{--@forelse($post as $p )--}}

                      @foreach($post as $p )
                      <tr>
                        <td>{{$p->id}}</td>
                        <td>{{$p->title}}</td>
                        <td>{{$p->slug}}</td>
                        <td>{{$p->meta_description}}</td>
                        <td>{{$p->files}}</td>
                        <td>{{ $p->categories->title or '' }}</td>
                        <td>
                          @foreach( $p->tags as $t )
                            {{ $t->title }},<br>
                          @endforeach
                        </td>
                        <td>{{$p->related_post}}</td>
                        <td>{{$p->created_at->format('d/m/Y H:i')}}</td>
                        <td>
                              <a href="{{ url('post/'.$p->id) }}">Show</a>
                              <a href="{{url('post/'.$p->id.'/edit')}}">Edit</a> 
                        </td>
                        <td>

                
                        <form method="post" action="{{ action('PostController@destroy', $p->id ) }}">
                          {{ csrf_field() }}
                          {{ method_field('Delete') }}
                          <button>Delete</button>
                        </form>


                        </td>
                      </tr>
                      

                      {{-- 
                          @empty
                          <tr>
                              <td colspan="100%">No Result...</td>
                          </tr> 
                          @endforelse
                      --}}

                      @endforeach

                    </table>

                  {{ $post->links() }}


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
