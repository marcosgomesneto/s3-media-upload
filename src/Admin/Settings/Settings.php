<?php

namespace AwsServicesKit\Admin\Settings;

defined( 'ABSPATH' ) || exit;

abstract class Settings {
	private static $instances = [];

	/**
	 * Tab id
	 *
	 * @var string
	 */
	protected $tab;
	protected $section;

	protected $type;

	protected $title;

	public function init() {
		$this->process_form();
		$vars = $this->process_page();
		$this->renderTemplate( $vars );
	}

	/**
	 * Get tab
	 *
	 * @return string
	 */
	public function getTab() {
		return $this->tab;
	}

	public function getTitle() {
		return $this->title;
	}

	/**
	 * Process page
	 *
	 * @return array
	 */
	abstract protected function process_page();

	protected function post_fields( $post ) {
		// Post fields
	}
	private function process_form() {
		if ( isset( $_POST[ $this->tab ] ) && check_admin_referer( $this->tab, $this->tab ) ) {
			$this->post_fields( $_POST );
		}
	}

	protected function renderTemplate( $vars = [] ) {
		if ( $this->type == 'section' )
			$tab_template = \S3_MEDIA_UPLOAD_PLUGIN_PATH . 'src/Admin/templates/settings/' . $this->tab . '/' . $this->section . '.php';
		else
			$tab_template = \S3_MEDIA_UPLOAD_PLUGIN_PATH . 'src/Admin/templates/settings/' . $this->tab . '.php';

		if ( file_exists( $tab_template ) ) {
			extract( $vars );
			require_once( $tab_template );
		}
	}
	public static function getInstance() {
		$class = get_called_class();
		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new $class();
		}
		return self::$instances[ $class ];
	}

}
