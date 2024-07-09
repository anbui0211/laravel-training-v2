<?php

namespace App\Traits;
// public function uploadImage(object $file, string $path, bool $isRename = true)

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Support\Facades\Log;

trait MediaTrait
{
    public function  uploadImage(object $file, string $storageLocation)
    {
        try {
            // get file name 
            $filename = $this->getFileName($file);

            $imagePath = '';
            switch ($storageLocation) {
                case 'LOCAL':
                    // Save the image file to local storage
                    $imagePath = $file->storeAs('public/images/post', $filename);
                    break;

                case 'S3':
                    $awsBucket = config('filesystems.disks.s3.bucket');
                    $this->ensureBucketExists($awsBucket);

                    $imageS3Path = $file->storeAs('images/post', $filename, 's3');
                    $imagePath =  $awsBucket . '/' . $imageS3Path;
                    break;
            }

            return $imagePath;
        } catch (Exception $e) {
            Log::error("[ERROR_UPLOAD_IMAGE] =>" . $e->getMessage());
        }
    }

    private function getFileName(object $file)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = uniqid() . '_' . time() . '.' . $extension;
        return $fileName;
    }

    private function  ensureBucketExists($bucketName)
    {
        $s3Client = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        try {
            $result = $s3Client->headBucket(['Bucket' => $bucketName]);
        } catch (AwsException $e) {
            if ($e->getAwsErrorCode() == 'NotFound') {
                $s3Client->createBucket(['Bucket' => $bucketName]);
            } else {
                throw $e;
            }
        }
    }
}
