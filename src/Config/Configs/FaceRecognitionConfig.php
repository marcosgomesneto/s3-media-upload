<?php

namespace AwsServicesKit\Config\Configs;

use AwsServicesKit\Config\AbstractConfig;

defined( 'ABSPATH' ) || exit;

class FaceRecognitionConfig extends AbstractConfig {

	/**
	 * Active Config
	 *
	 * @since 1.0.0
	 * @var boolean
	 */
	public $enabled = false;

	/**
	 * Collection Config
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $collection = '-1';

	/**
	 * Collections List Config
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $collections = [];

	/**
	 * Form Theme Config
	 *
	 * @var string light|dark
	 */
	public $form_theme = 'light';

	/**
	 * Form Title
	 *
	 * @var string
	 */
	public $form_title = 'Search Products by Image';

	/**
	 * Form Description
	 *
	 * @var string
	 */
	public $form_description = "Upload a person`s face to find all photos they appear in within our database.";

	/**
	 * Button Camera Text
	 *
	 * @var string
	 */
	public $button_camera_text = 'From Camera';

	/**
	 * Button Gallery Text
	 *
	 * @var string
	 */
	public $button_gallery_text = 'From Gallery';

	/**
	 * Button Search Text
	 *
	 * @var string
	 */
	public $button_search_text = 'Search Now';
}
