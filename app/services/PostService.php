<?php

namespace App\Services;

use App\Repositories\Interfaces\ImageRepositoryInterface;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Traits\MediaTrait;
use Illuminate\Support\Facades\DB;

class PostService
{
    use MediaTrait;

    public function __construct(private PostRepositoryInterface $postRepo, private ImageRepositoryInterface $imageRepo)
    {
    }

    public function index(array $queryParam)
    {
        $relation = ['user:id,name'];
        return $this->postRepo->findWithRelation($relation, $queryParam);
    }

    public function store(array $data)
    {
        // Get storage save image 
        $storageLocation = config('filesystems.disks.storage_location');
        $images = $data['images'];
        DB::beginTransaction();

        try {
            // Save post
            $post = $this->postRepo->insert([
                'title' => $data['title'],
                'content' => $data['content'],
                'user_id' => $data['user_id'],
            ]);
            $postId = $post->id;

            // Save multiple image
            $imagesPathResult = [];
            if (isset($images) && is_array($images)) {
                foreach ($images as $i) {
                    // Upload image and add image path to response
                    $imagePath = $this->uploadImage($i, $storageLocation);
                    $imagesPathResult[] = $imagePath;

                    // save image
                    $this->imageRepo->insert([
                        'path' => $imagePath,
                        'post_id' => $postId,
                    ]);
                }
            }

            DB::commit();

            return  [
                'image_paths' => $imagesPathResult,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
