<?php

namespace App\Repositories;

use App\Models\Image;
use App\Repositories\Interfaces\ImageRepositoryInterface;

class ImageRepository implements ImageRepositoryInterface
{
    public function __construct(private Image $model)
    {
    }

    public function insert(array $data)
    {
        return $this->model->create($data);
    }
}
