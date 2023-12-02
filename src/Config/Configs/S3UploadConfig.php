<?php

namespace AwsServicesKit\Config\Configs;

use AwsServicesKit\Config\AbstractConfig;

defined( 'ABSPATH' ) || exit;

class S3UploadConfig extends AbstractConfig {

	/**
	 * S3 Bucket Name ID
	 *
	 * @var string
	 */
	public $bucket_name;


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
	public $include_folders = [];

	/**
	 * Upload folder base directory
	 *
	 * @var string
	 */
	public $basedir = 'uploads';

}
