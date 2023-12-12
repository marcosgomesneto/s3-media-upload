<?php

namespace AwsServicesKit\Admin\Settings;

use AwsServicesKit\Services\RekognitionService;
use AwsServicesKit\Config\ConfigLoader;

defined( 'ABSPATH' ) || exit;

class FaceRecognitionSettings extends Settings {
	public function __construct() {
		$this->type = 'section';
		$this->tab = 'aws-rekognition';
		$this->section = 'woocommerce-product-search';
		$this->title = 'WooCommerce Product Search';
	}

	public function process_page() {
		$config = ConfigLoader::get();

		$form_themes = [ 
			'light' => 'Light',
			'dark' => 'Dark'
		];

		$collections = $config->face_recognition->collections;

		if ( empty( $collections ) ) {
			$rekognition = RekognitionService::getInstance();
			$collections = $rekognition->getCollectionsIds();
			$config->face_recognition->update( 'collections', $collections );
			$config->face_recognition->save();
		}
		return [ 
			'collections' => $collections,
			'options' => $config->face_recognition,
			'form_themes' => $form_themes
		];
	}

	protected function post_fields( $post ) {
		$config = ConfigLoader::get();

		$config->face_recognition->update( 'enabled', isset( $_POST['awsrek_active'] ) ? true : false );
		$config->face_recognition->update( 'collection', sanitize_text_field( $_POST['awsrek_collection'] ) );
		$config->face_recognition->update( 'form_theme', sanitize_text_field( $_POST['awsrek_form_theme'] ) );
		$config->face_recognition->update( 'form_title', sanitize_text_field( $_POST['awsrek_form_title'] ) );
		$config->face_recognition->update( 'button_gallery_text', sanitize_text_field( $_POST['awsrek_button_gallery_text'] ) );
		$config->face_recognition->update( 'button_camera_text', sanitize_text_field( $_POST['awsrek_button_camera_text'] ) );
		$config->face_recognition->update( 'button_search_text', sanitize_text_field( $_POST['awsrek_button_search_text'] ) );

		$config->face_recognition->save();
	}
}
