<?php

namespace AwsServicesKit\Config;

use AwsServicesKit\Config\Configs\FaceRecognitionConfig;
use AwsServicesKit\Config\Configs\GeneralConfig;
use AwsServicesKit\Config\Configs\S3UploadConfig;

defined( 'ABSPATH' ) || exit;

class ConfigLoader {
	/**
	 * Singleton instance
	 *
	 * @var ConfigLoader
	 */
	private static $instance;

	private $key = 'aws_services_kit_options';

	/**
	 * Face Recognition Config
	 *
	 * @var FaceRecognitionConfig
	 */
	public $face_recognition;

	public $general;
	public $s3_upload;

	private $options;

	public function __construct() {
		$options = get_option( $this->key );

		$this->options = $options;
		$this->face_recognition = new FaceRecognitionConfig( $this, 'face_recognition' );
		$this->general = new GeneralConfig( $this, 'general' );
		$this->s3_upload = new S3UploadConfig( $this, 's3_upload' );
	}

	public function getKey() {
		return $this->key;
	}

	public function getOptions() {
		return $this->options;
	}

	/**
	 * Get Config Loader Singleton
	 *
	 * @return ConfigLoader
	 */
	public static function get() {
		if ( self::$instance === null ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
}
