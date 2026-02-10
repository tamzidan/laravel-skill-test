<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a paginated list of active posts.
     */
    public function index(): JsonResponse
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
    public function create(): string
    {
        return 'posts.create';
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Auth::user()->posts()->create($request->validated());

        return response()->json(new PostResource($post), 201);
    }

    /**
     * Display the specified active post.
     */
    public function show(Post $post): JsonResponse
    {
        $isActive = ! $post->is_draft
                    && $post->published_at !== null
                    && $post->published_at->lte(now());

        if (! $isActive) {
            abort(404);
        }

        $post->load('user');

        return response()->json(new PostResource($post));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post): string
    {
        $this->authorize('edit', $post);

        return 'posts.edit';
    }

    /**
     * Update the specified post in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $this->authorize('update', $post);

        $post->update($request->validated());

        return response()->json(new PostResource($post->refresh()));
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(null, 204);
    }
}
