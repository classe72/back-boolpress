@extends('layouts.admin')

@section('content')

@if($post->cover_image)
<img class="img-fluid" src="{{asset('storage/' . $post->cover_image)}}" alt="">
@else
<div class="placeholder p-5 bg-secondary">Placeholder</div>

@endif
<h1>{{$post->title}}</h1>
<h5>{{$post->slug}}</h5>
<div class="category">
    <strong>Category:</strong>
    {{ $post->category ? $post->category->name : 'Uncategorized'}}
</div>


<div class="tags">
    <strong>Tags:</strong>
    @if(count($post->tags) > 0 )


    @foreach ($post->tags as $tag)
    <span>#{{$tag->name}} </span> <!-- #css #php #js -->
    @endforeach

    @else
    <span>Not tag associated to the current post</span>
    @endif

</div>


<div class="content">
    {{$post->body}}
</div>
@endsection
