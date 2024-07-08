<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;

class PostController extends Controller
{
    public function __construct(private PostService $postService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request)
    {
        $queryParams =  $request->validated();
        $data = $this->postService->index($queryParams);
        return  PostResource::collection($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user:id,name']);
        return new PostResource($post);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest $request)
    {
        $validatedData = $request->validated();
        $this->postService->store($validatedData);

        return response()->json([
            "message" => "create success",
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $validatedData = $request->validated();
        $this->postService->update($validatedData);

        return response()->json([
            'message' => "update successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->postService->destroy($post->id);
        return response()->json([
            'message' => "delete successfully"
        ]);
    }
}
