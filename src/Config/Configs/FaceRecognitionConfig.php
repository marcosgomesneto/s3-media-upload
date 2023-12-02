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


}
