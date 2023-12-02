<?php

namespace AwsServicesKit\Config;

use AwsServicesKit\Config\ConfigLoader;

defined( 'ABSPATH' ) || exit;

abstract class AbstractConfig {

	protected $key;

	protected $options = [];

	private $config;

	/**
	 * Constructor
	 *
	 * @param ConfigLoader $config
	 * @param string $key
	 */
	public function __construct( $config, $key ) {
		$this->key = $key;
		$this->config = $config;
		$options = $this->config->getOptions();
		if ( isset( $options[ $key ] ) ) {
			$this->options = $options[ $key ];
			foreach ( $options[ $key ] as $option_key => $option_value ) {
				if ( property_exists( $this, $option_key ) ) {
					$this->$option_key = $option_value;
				}

			}
		}
	}

	public function update( $key, $value ) {
		$parentProperties = get_class_vars( get_parent_class( $this ) );
		$childProperties = get_object_vars( $this );
		$exclusiveChildProperties = array_diff_key( $childProperties, $parentProperties );

		if ( array_key_exists( $key, $exclusiveChildProperties ) ) {
			$this->options[ $key ] = $value;
			$this->$key = $value;
		}
	}

	public function save() {
		$options = $this->config->getOptions();
		$options = array_merge( is_array( $options ) ? $options : [], [ $this->key => $this->options ] );
		update_option( $this->config->getKey(), $options );
	}

}