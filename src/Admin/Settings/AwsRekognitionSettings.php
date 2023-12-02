<?php

namespace AwsServicesKit\Admin\Settings;

use AwsServicesKit\Config\ConfigLoader;

defined( 'ABSPATH' ) || exit;

class AwsRekognitionSettings extends Settings {
	public function __construct() {
		$this->type = 'page';
		$this->tab = 'aws-rekognition';
		$this->title = 'AWS Rekognition';
	}

	public function process_page() {
		return [];
	}
}
