<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //$posts = Post::orderByDesc('id')->get();
        $posts = Auth::user()->posts()->paginate(5);
        //dd(Auth::user()->posts);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        //dd($categories);
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {

        //validate data
        $val_data = $request->validated();
        //dd($val_data);
        // Save the input cover_image
        if ($request->hasFile('cover_image')) {
            $cover_image = Storage::put('uploads', $request->cover_image);
            //dd($cover_image);
            // replace the value of cover_image inside $val_data
            $val_data['cover_image'] = $cover_image;
        }
        // generate post slug
        $post_slug = Post::generateSlug($request->title);
        $val_data['slug'] = $post_slug;

        // assign the current post to the authenticated user
        $val_data['user_id'] = Auth::id();

        // create posts
        $post = Post::create($val_data);

        // attach the selected tags
        if ($request->has('tags')) {
            $post->tags()->attach($val_data['tags']);
        }
        // redirect
        return to_route('admin.posts.index')->with('message', "Posts id: $post->id added successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {

        /* TODO: Allow only the current user to edit a post */
        // validate data
        $val_data = $request->validated();
        //dd($val_data);

        // check if the request has a cover_image field
        if ($request->hasFile('cover_image')) {
            // check if the current post has an image if yes, delete it
            if ($post->cover_image) {
                Storage::delete($post->cover_image);
            }
            $cover_image = Storage::put('uploads', $val_data['cover_image']);
            //dd($cover_image);
            // replace the value of cover_image inside $val_data
            $val_data['cover_image'] = $cover_image;
        }
        //dd($val_data);

        // update the slug
        $post_slug = Post::generateSlug($val_data['title']);
        $val_data['slug'] = $post_slug;
        //dd($val_data);
        // update the resource
        $post->update($val_data);

        if ($request->has('tags')) {
            $post->tags()->sync($val_data['tags']);
        } else {
            $post->tags()->sync([]);
        }
        // redirect to a get route
        return to_route('admin.posts.index')->with('message', "Post id: $post->id updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        /* TODO: Allow only the current user to delete a post */

        if ($post->cover_image) {
            Storage::delete($post->cover_image);
        }

        $post->delete();
        return to_route('admin.posts.index')->with('message', 'Post Deleted successfully');
    }
}
