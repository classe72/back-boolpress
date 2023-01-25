<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    //


    public function index()
    {
        return response()->json([
            'success' => true,
            'results' => Post::with(['category', 'tags'])->orderByDesc('id')->paginate(5)
        ]);

        //return Post::orderByDesc('id')->paginate(5);
    }
}
