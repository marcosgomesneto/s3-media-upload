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

    /**
     * Remove local files when uploaded in s3
     *
     * @var bool
     */
    public $remove_local;

    /**
     * Folders to include
     *
     * @var array
     */
    public $include_folders;

    public function __construct()
    {
        $options = get_option('s3_media_upload_options');
        $this->accessKeyId =  $options['access_key_id'];
        $this->secretAccessKey = $options['secret_access_key'];
        $this->region =  $options['region'];
        $this->bucket_name = $options['bucket_name'];
        $this->basedir = 'uploads';
        $this->remove_local = $options['remove_local'] == 1 ? true : false;
        $this->include_folders = explode(PHP_EOL, trim($options['include_folders']));
    }

    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}
