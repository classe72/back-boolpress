@extends('layouts.admin')

@section('content')


<h1>Create a new Post</h1>
@if ($errors->any())

<div class="alert alert-danger" role="alert">
    <ul>
        @foreach ($errors->all() as $error )
        <li>{{$error}}</li>
        @endforeach
    </ul>
</div>


@endif


<form action="{{route('admin.posts.store')}}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="learn laravel 9" aria-describedby="titleHelper" value="{{old('title')}}">
        <small id="titleHelper" class="text-muted">Add a title for the current post, max 100 characters, must be unique</small>
    </div>
    @error('title')
    <div class="alert alert-danger" role="alert">
        {{$message}}
    </div>
    @enderror


    <div class="mb-3">

        <label for="cover_image" class="form-label">Cover Image</label>
        <input type="file" name="cover_image" id="cover_image" class="form-control  @error('cover_image') is-invalid @enderror" placeholder="" aria-describedby="coverImageHelper">
        <small id="coverImageHelper" class="text-muted">Add the post cover image</small>

    </div>
    @error('cover_image')
    <div class="alert alert-danger" role="alert">
        {{$message}}
    </div>
    @enderror



    <div class="mb-3">
        <label for="category_id" class="form-label">Categories</label>
        <select class="form-select form-select-sm @error('category_id') 'is-invalid' @enderror" name="category_id" id="category_id">
            <option value="">No categoy</option>

            @foreach ($categories as $category )
            <option value="{{$category->id}}" {{ old('category_id') ? 'selected' : '' }}>{{$category->name}}</option>
            @endforeach

        </select>
    </div>
    @error('category_id')
    <div class="alert alert-danger" role="alert">
        {{$message}}
    </div>
    @enderror



    <!-- TODO: Show alla tags to attach to the post-->
    <div class="mb-3">
        <label for="tags" class="form-label">Tags</label>
        <select multiple class="form-select form-select-sm" name="tags[]" id="tags">
            <option value="" disabled>Select a tag</option>


            @forelse ($tags as $tag)
            <option value="{{$tag->id}}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{$tag->name}}</option>
            @empty
            <option value="" disabled>Sorry 😥 no tags in the system</option>
            @endforelse

        </select>
    </div>


    <!-- Versione con checkboxes
    <div class="form-group">
        <p>Seleziona i tag:</p>
        @foreach ($tags as $tag)
        <div class="form-check @error('tags') is-invalid @enderror">


            <label class="form-check-label">
                <input name="tags[]" type="checkbox" value="{{ $tag->id }}" class="form-check-input" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                {{ $tag->name }}
            </label>
        </div>
        @endforeach

        @error('tags')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
 -->



    <div class="mb-3">
        <label for="body" class="form-label">Body</label>
        <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="body" rows="5">{{old('body')}}</textarea>
    </div>
    @error('body')
    <div class="alert alert-danger" role="alert">
        {{$message}}
    </div>
    @enderror
    <button type="submit" class="btn btn-primary">Create</button>
</form>
@endsection
