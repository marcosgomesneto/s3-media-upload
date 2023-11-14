<?php

namespace S3MediaUpload\Utils;

class Config
{
    /**
     * Singleton instance
     *
     * @var Config
     */
    private static $instance;

    public $accessKeyId;
    public $secretAccessKey;
    public $region;
    public $bucket_name;
    public $basedir;

    public function __construct()
    {
        $options = get_option('s3_media_upload_options');
        $this->accessKeyId =  $options['access_key_id'];
        $this->secretAccessKey = $options['secret_access_key'];
        $this->region =  $options['region'];
        $this->bucket_name = $options['bucket_name'];
        $this->basedir = 'uploads';
    }

    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}
