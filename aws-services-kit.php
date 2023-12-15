<?php

/**
 * AWS Services Kit
 *
 * @link              https://github.com/marcosgomesneto/aws-services-kit
 * @since             1.0.0
 * @package           AwsServicesKit
 *
 * @wordpress-plugin
 * Plugin Name:       		AWS Services Kit
 * Plugin URI:        		https://github.com/marcosgomesneto/aws-services-kit
 * Description:       		AWS Services: S3 Upload, Face Recognition Search, WooCommerce Integration
 * Version:           		1.0.1
 * Requires at least: 		5.2
 * Requires PHP:      		7.0
 * WC requires at least:	3.0
 * WC tested up to:      	8.1.1
 * Author:            		Marcos Gomes Neto
 * Author URI:        		https://github.com/marcosgomesneto
 * Text Domain:       		aws-services-kit
 * License:           		GPLv2 or later
 * License URI:       		http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) || exit;

//Define globals
define( 'S3_MEDIA_UPLOAD_PLUGIN_NAME', 'aws-services-kit' );
define( 'S3_MEDIA_UPLOAD_I18N_NAME', 'aws-services-kit' );
define( 'S3_MEDIA_UPLOAD_PLUGIN_VERSION', '1.0.0' );
define( 'S3_MEDIA_UPLOAD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'S3_MEDIA_UPLOAD_PLUGIN_ASSETS_URL', \S3_MEDIA_UPLOAD_PLUGIN_URL . 'assets/' );
define( 'S3_MEDIA_UPLOAD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'S3_MEDIA_UPLOAD_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'S3_MEDIA_UPLOAD_DIR_NAME', dirname( plugin_basename( __FILE__ ) ) );
define( 'S3_MEDIA_UPLOAD_PLUGIN_FILE_NAME', __FILE__ );
define( 'S3_MEDIA_UPLOAD_SETTINGS_BASE_URL', admin_url( 'tools.php?page=' . \S3_MEDIA_UPLOAD_PLUGIN_NAME ) );

require S3_MEDIA_UPLOAD_PLUGIN_PATH . 'vendor/autoload.php';

/**
 * Global function-holder. Works similar to a singleton's instance().
 *
 * @since 1.0.0
 *
 * @return AwsServicesKit\Core
 */
function aws_services_kit_init() {
	/**
	 * @var \AwsServicesKit\Core
	 */
	static $core;

	if ( ! isset( $core ) ) {
		$core = new \AwsServicesKit\Core();
	}

	return $core;
}

aws_services_kit_init();
