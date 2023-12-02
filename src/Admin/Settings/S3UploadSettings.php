<?php

namespace AwsServicesKit\Admin\Settings;

use AwsServicesKit\Config\ConfigLoader;

defined( 'ABSPATH' ) || exit;

class S3UploadSettings extends Settings {
	public function __construct() {
		$this->type = 'page';
		$this->tab = 'aws-s3';
		$this->title = 'AWS S3';
	}

	public function process_page() {
		$config = ConfigLoader::get();

		return [ 
			'options' => $config->s3_upload
		];
	}

	protected function post_fields( $post ) {
		$config = ConfigLoader::get();
		$config->s3_upload->update( 'bucket_name', sanitize_text_field( $_POST['bucket_name'] ) );
		$config->s3_upload->update( 'remove_local', isset( $_POST['remove_local'] ) ? true : false );
		$config->s3_upload->update( 'include_folders', explode( PHP_EOL, sanitize_textarea_field( $_POST['include_folders'] ) ) );
		$config->s3_upload->save();
	}
}
