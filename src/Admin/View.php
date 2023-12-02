<?php

namespace AwsServicesKit\Admin;

use AwsServicesKit\Admin\Settings\AwsRekognitionSettings;
use AwsServicesKit\Admin\Settings\FaceRecognitionSettings;
use AwsServicesKit\Admin\Settings\GeneralSettings;
use AwsServicesKit\Admin\Settings\S3UploadSettings;
use AwsServicesKit\Admin\Settings\Settings;

defined( 'ABSPATH' ) || exit;

class View {
	public $template_path;

	public function __construct() {
		$this->template_path = S3_MEDIA_UPLOAD_PLUGIN_PATH . 'src/Admin/templates/';
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
	}

	public function admin_menu() {
		add_submenu_page(
			'tools.php',
			'AWS Services Kit',
			'AWS Services Kit',
			'manage_options',
			'aws-services-kit',
			[ $this, 'render_html' ]
		);
	}

	/**
	 * Get current tab
	 * 
	 * @since 1.0.0
	 * @return Settings
	 */
	protected function get_current_tab() {
		$tabs = $this->get_tabs();
		if ( ! isset( $_GET['tab'] ) || ! isset( $tabs[ htmlspecialchars( $_GET['tab'] ) ] ) ) {
			return reset( $tabs );
		}

		$current_tab = htmlspecialchars( $_GET['tab'] );
		/**
		 * @var Settings
		 */
		return $tabs[ $current_tab ];
	}

	/**
	 * Get Current Section
	 * 
	 * @since 1.0.0
	 * @return Settings|null
	 */
	protected function get_current_section() {
		$current_tab = $this->get_current_tab();
		$current_tab_name = $current_tab->getTab();
		$sections = $this->get_sections();
		if ( ! isset( $_GET['section'] ) )
			return null;

		$current_section = htmlspecialchars( $_GET['section'] );
		if ( ! isset( $sections[ $current_tab_name ][ $current_section ] ) )
			return null;

		/**
		 * @var Settings
		 */
		return $sections[ $current_tab->getTab()][ $current_section ];
	}

	/**
	 * Tabs
	 * 
	 * @since 1.0.0
	 * @return Settings[]
	 */
	protected function get_tabs() {
		return [ 
			'general' => GeneralSettings::getInstance(),
			'aws-s3' => S3UploadSettings::getInstance(),
			'aws-rekognition' => AwsRekognitionSettings::getInstance()
		];
	}

	/**
	 * Sections
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_sections() {
		return [ 
			'aws-rekognition' => [ 
				'woocommerce-product-search' => FaceRecognitionSettings::getInstance()
			]
		];
	}

	public function render_html() {
		$current_section = $this->get_current_section();
		$current_tab = $this->get_current_tab();
		$baseUrl = admin_url( 'tools.php?page=aws-services-kit' );

		include_once( $this->template_path . 'settings.php' );

		if ( $current_section !== null ) {
			$current_section->init();
		} else {
			$current_tab->init();
		}
	}
}