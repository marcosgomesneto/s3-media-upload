<?php

namespace S3MediaUpload\Services;

use Aws\S3\S3Client;
use Exception;
use S3MediaUpload\Utils\Config;

defined('ABSPATH') || exit;

class S3Service
{
    /**
     * Singleton instance
     *
     * @var S3Service
     */
    private static $instance;

    /**
     * S3 Instance API Client
     *
     * @var S3Client
     */
    private $s3;

    /**
     * Bucket region in amazon s3
     *
     * @var string
     */
    private $region;

    /**
     * Bucket name in amazon s3
     *
     * @var string
     */
    private $bucket_name;

    /**
     * Basedir in s3 bucket
     *
     * @var string
     */
    private $basedir;

    public function __construct()
    {
        $config = Config::get();
        $this->bucket_name = $config->bucket_name;
        $this->basedir = $config->basedir;
        $this->region = $config->region;

        $this->s3 = new S3Client([
            'version' => 'latest',
            'region'  => $config->region,
            'credentials' => [
                'key'    => $config->accessKeyId,
                'secret' => $config->secretAccessKey,
            ],
        ]);
    }

    /**
     * Upload file to amazon s3 bucket
     *
     * @param string $local_file_path
     * @param string $remote_dir
     * @return \Aws\Result
     */
    public function uploadFile($local_file_path, $remote_path = null)
    {
        $base_upload_dir = wp_upload_dir()['basedir'];
        $remote_file_name = $this->basedir . str_replace($base_upload_dir, '', $local_file_path);

        return $this->s3->putObject([
            'Bucket' => $this->bucket_name,
            'Key'    => $remote_file_name,
            'Body'   => fopen($local_file_path, 'rb'),
            'ACL'    => 'public-read',
        ]);
    }

    public function getBaseUrl()
    {
        return "https://{$this->bucket_name}.s3.{$this->region}.amazonaws.com/uploads";
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}
