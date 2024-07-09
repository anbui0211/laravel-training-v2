<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{
    public function __construct(private Post $model)
    {
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findWithRelation(array $relation, array $queryParams)
    {
        $page =  $queryParams['page'];
        $limit = $queryParams['limit'];
        $sort =  $queryParams['sort'];
        $direction = $queryParams['direction'];

        // Get the paging results and specify the current page
        $result = $this->model
            ->with($relation)
            ->orderBy($sort, $direction)
            ->paginate($limit, ['*'], 'page', $page);

        // Retain other pagination parameters when navigating between pages
        return  $this->model
            ->with($relation)
            ->orderBy($sort, $direction)
            ->paginate($limit);
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function insert(array $data)
    {
        $this->model->fill($data);
        $this->model->save();
        return $this->model;
    }

    public function update(array $data, $id)
    {
        $post = $this->model->findOrFail($id);
        $post->update($data);
    }

    public function delete($id)
    {
        $post = $this->model->findOrFail($id);
        $post->delete($post);
    }
}
