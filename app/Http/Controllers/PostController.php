<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a paginated list of active posts.
     */
    public function index()
    {
        $posts = Post::with('user')
            ->active()
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
    public function store(StorePostRequest $request)
    {
        $post = Auth::user()->posts()->create($request->validated());

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
        $this->authorize('edit', $post);

        return 'posts.edit';
    }

    /**
     * Update the specified post in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $post->update($request->validated());

        return response()->json($post);
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(null, 204);
    }
}
