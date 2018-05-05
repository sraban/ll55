@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    
                    @if( count($errors->post_form) > 0 )
                        <div class="alert alert-danger">
                          <ul>
                            @foreach( $errors->post_form->all() as $error )
                              <li>{{ $error }}</li>
                            @endforeach
                          </ul>
                        </div>
                    @endif
                    

                       
          <form method="post" enctype="multipart/form-data" action="{{ route('post.update', $post->id ) }}" >
          {{csrf_field()}}{{method_field('PUT')}}
            <div class="form-group">
              <label for="exampleFormControlInput1">Name</label>
              <input type="text" name="title" class="form-control" value="{{ (old('title')) ? old('title') : $post->title   }}" id="exampleFormControlInput1" placeholder="">
            </div>


            <div class="form-group">
              <label for="exampleFormControlInput2">Slug</label>
              <input type="text" readonly name="slug" value="@if(old('slug')) {{old('slug')}} @else {{ $post->slug }}@endif" class="form-control" id="exampleFormControlInput2" placeholder="">
            </div>


            <div class="form-group">
              <label for="exampleFormControlSelect1">Category</label>
              <select class="form-control" name="category" id="exampleFormControlSelect1">
                @foreach($category as $k => $c )
                <option {{ ((old('category') == $k) ? 'selected': (($post->category_id == $k) ? 'selected':'') ) }} value="{{ $k }}">{{ $c }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="exampleFormControlSelect2">Tags</label>
              <select multiple class="form-control" id="exampleFormControlSelect2" name="tags[]">
                @foreach($tag as $k => $c )
                <option @if( old('tags')) {{ ( collect( old('tags') )->contains($k)  ? 'selected' : '' ) }} @else {{ ( $db_old_tags->contains($k)  ? 'selected' : '' ) }} @endif value="{{ $k }}">{{ $c }}</option>
                @endforeach
              </select>
            </div>

            
            <div class="form-group">
              <label for="exampleFormControlSelect3">Related Post</label>
              <select multiple class="form-control" id="exampleFormControlSelect3" name="related_post[]">
                @foreach($related_post as $k => $c )
                <option @if( old('related_post') ) {{ in_array($k, old('related_post') ) ? 'selected' : '' }} @else {{ in_array($k, json_decode($post->related_post) ) ? 'selected' : '' }}  @endif value="{{ $k }}">{{ $c }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="exampleFormControlFile1">Files</label>
              <input type="file" name="files[]" multiple class="form-control-file" id="exampleFormControlFile1">
              {{ $post->files }}
            </div>

            <div class="form-group">
                <label for="exampleFormControlTextarea1">Description</label>
                <textarea name="meta_description" class="form-control" id="exampleFormControlTextarea1" rows="3">{{ (old('meta_description') ? old('meta_description') : ($post->meta_description ? $post->meta_description : '' ) )  }}</textarea>
            </div>

            
            
            <button type="submit" class="btn btn-primary">Submit</button>

            <!-- 
            <fieldset class="form-group">
                <div class="row">
                  <legend class="col-form-label col-sm-2 pt-0">Radios</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1" checked>
                      <label class="form-check-label" for="gridRadios1">
                        First radio
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2">
                      <label class="form-check-label" for="gridRadios2">
                        Second radio
                      </label>
                    </div>
                    <div class="form-check disabled">
                      <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios3" value="option3" disabled>
                      <label class="form-check-label" for="gridRadios3">
                        Third disabled radio
                      </label>
                    </div>
                  </div>
                </div>
              </fieldset>

             <div class="form-group row">
                <div class="col-sm-2">Checkbox</div>
                <div class="col-sm-10">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gridCheck1">
                    <label class="form-check-label" for="gridCheck1">
                      Example checkbox
                    </label>
                  </div>
                </div>
              </div> -->

              


          </form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
