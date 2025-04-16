<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created comment.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);
        
        $comment = new Comment([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);
        
        $comment->save();
        
        $comment->load('user');
        
        if ($request->ajax()) {
            return response()->json([
                'comment' => $comment,
                'user' => [
                    'name' => $comment->user->name ?? $comment->user->first_name . ' ' . $comment->user->last_name,
                    'avatar' => $comment->user->avatar ?? null,
                ],
                'created_at_formatted' => $comment->created_at->diffForHumans(),
            ]);
        }
        
        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if the user is authorized to update this comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        $comment->content = $validated['content'];
        $comment->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
            ]);
        }
        
        return redirect()->back()->with('success', 'Comment updated successfully.');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        // Only allow users to delete their own comments
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $comment->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

    /**
     * Load more comments for a post.
     */
    public function loadMore(Request $request, Post $post)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        
        $comments = $post->comments()
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get();
        
        return response()->json([
            'comments' => $comments,
            'hasMore' => $post->comments()->count() > ($offset + $limit),
        ]);
    }
}
