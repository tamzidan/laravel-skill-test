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

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'is_draft' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $post = Auth::user()->posts()->create($validated);

        return response()->json($post, 201);
    }

    /**
     * Display the specified active post.
     */
    public function show(Post $post)
    {
        if ($post->is_draft || ($post->published_at && $post->published_at->isFuture())) {
            abort(404);
        }

        $post->load('user');

        return response()->json($post);
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized');
        }

        return 'posts.edit';
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'is_draft' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $post->update($validated);

        return response()->json($post);
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized');
        }

        $post->delete();

        return response()->json(null, 204);
    }
}
