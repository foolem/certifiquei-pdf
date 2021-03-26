<?php

namespace App;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Exception;

class S3Storage implements StorageInterface {

    /**
     * @var S3Client
     * $s3Client
     */
    private $s3Client;

    /**
     * S3Storage constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->s3Client = new S3Client([
            'region'  => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);
        $this->registerStreamWrapper();
    }

    /**
     * @throws Exception
     */
    private function registerStreamWrapper()
    {
        try {
            $this->s3Client->registerStreamWrapper();
        } catch (S3Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param string $fileName
     * @return string
     * @throws Exception
     */
    public function getFileUrl(string $fileName): string
    {
        $fileUrl = null;
        try {
            $fileUrl = $this->s3Client->getObjectUrl(env('AWS_BUCKET'), $fileName);
        } catch (S3Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $fileUrl;
    }

    /**
     * @return S3Client
     */
    public function getS3Client(): S3Client
    {
        return $this->s3Client;
    }

    /**
     * @param S3Client $s3Client
     */
    public function setS3Client(S3Client $s3Client): void
    {
        $this->s3Client = $s3Client;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return 's3://' . env('AWS_BUCKET') . '/';
    }
}
