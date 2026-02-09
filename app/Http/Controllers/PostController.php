<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a paginated list of active posts.
     */
    public function index()
    {
        $posts = Post::with('user')
            ->where('is_draft', false)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(20);

        return response()->json($posts);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return 'posts.create';
    }
}
