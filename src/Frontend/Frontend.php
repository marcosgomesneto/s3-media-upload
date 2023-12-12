<?php

namespace AwsServicesKit\Frontend;

use AwsServicesKit\Config\ConfigLoader;
use AwsServicesKit\Services\RekognitionService;
use AwsServicesKit\Services\S3Service;

defined( 'ABSPATH' ) || exit;

class Frontend {
	public function __construct() {
		add_shortcode( 'awskit-face-search-form', [ $this, 'face_search_form_shortcode' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_filter( 'posts_search', [ $this, 'posts_search' ], 10, 2 );
	}

	public function face_search_form_shortcode() {
		ob_start();
		$options = ConfigLoader::get()->getOptions()['face_recognition'];
		require_once S3_MEDIA_UPLOAD_PLUGIN_PATH . 'src/Frontend/templates/face-search-form.php';
		return ob_get_clean();
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'awskit-style', \S3_MEDIA_UPLOAD_PLUGIN_ASSETS_URL . 'frontend/css/style.css', [], \S3_MEDIA_UPLOAD_PLUGIN_VERSION );
		wp_enqueue_script( 'awskit-js', \S3_MEDIA_UPLOAD_PLUGIN_ASSETS_URL . 'frontend/js/awskit.js', [], \S3_MEDIA_UPLOAD_PLUGIN_VERSION );
	}

	public function posts_search( $search, $wp_query ) {
		global $wpdb;
		if ( ! is_admin() && $wp_query->is_main_query() && $wp_query->is_search() && isset( $_GET['awskit_image'] ) ) {
			try {
				$recogniztion = RekognitionService::getInstance();
				$productIds = $recogniztion->searchFacesByImage( $_GET['awskit_image'] );
			} catch (\Exception $e) {
				$productIds = [];
			}
			$search = $wpdb->prepare( "AND ({$wpdb->posts}.ID IN (" . implode( ',', $productIds ) . "))" );

		}
		return $search;
	}
}
