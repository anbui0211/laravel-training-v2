<?php

namespace App\Services;

use App\Repositories\PostRepositoryInterface;

class PostService
{
    public function __construct(private PostRepositoryInterface $postRepo)
    {
    }

    public function index(array $queryParam)
    {
        $relation = ['user:id,name'];
        return $this->postRepo->findWithRelation($relation, $queryParam);
    }

    public function store(array $data)
    {
        $postCreate = [
            'title' => $data['title'],
            'content' => $data['content'],
            'user_id' => $data['user_id'],
        ];

        $this->postRepo->insert($postCreate);
    }

    public function update(array $data)
    {
        $id = $data['id'];
        $arrUpdate = [
            'title' => $data['title'],
            'content' => $data['content'],
        ];

        $this->postRepo->update($arrUpdate, $id);
    }

    public function destroy($id)
    {
        return $this->postRepo->delete($id);
    }
}
