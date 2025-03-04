<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:post-list|post-create|post-edit|post-delete'], ['only' => ['index']]);
        $this->middleware(['permission:post-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:post-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:post-delete'], ['only' => ['destroy']]);
        $this->middleware(['permission:post-show'], ['only' => ['show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('posts.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = Post::create($request->all());
        return redirect()->route('posts.index')->with('success', 'Post saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $post->update($request->all());
        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    /**
     * Display the specified post by ID.
     */
    public function showPostById($id)
    {
        $post = Post::findOrFail($id);
        return view('post', compact('post'));
    }
}
