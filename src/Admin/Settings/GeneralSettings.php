<?php

namespace AwsServicesKit\Admin\Settings;

use AwsServicesKit\Config\ConfigLoader;

defined( 'ABSPATH' ) || exit;

class GeneralSettings extends Settings {
	public function __construct() {
		$this->type = 'page';
		$this->tab = 'general';
		$this->title = 'General';
	}

	public function process_page() {
		$config = ConfigLoader::get();

		return [ 
			'baseUrl' => admin_url( 'tools.php?page=aws-services-kit' ),
			'options' => $config->general
		];
	}

	protected function post_fields( $post ) {
		$config = ConfigLoader::get();
		$config->general->update( 'access_key_id', sanitize_text_field( $_POST['access_key_id'] ) );
		$config->general->update( 'secret_access_key', sanitize_text_field( $_POST['secret_access_key'] ) );
		$config->general->update( 'region', sanitize_text_field( $_POST['region'] ) );
		$config->general->save();
	}
}
