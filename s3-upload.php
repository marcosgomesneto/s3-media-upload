<?php

/**
 * S3 Media Upload
 *
 * @link              https://github.com/marcosgomesneto/woocommerce-pagarme-pix-payment
 * @since             1.1.0
 * @package           S3_Media_Upload
 *
 * @wordpress-plugin
 * Plugin Name:       		S3 Media Upload
 * Plugin URI:        		https://github.com/marcosgomesneto/s3-media-upload
 * Description:       		Upload Files to S3
 * Version:           		1.0.0
 * Requires at least: 		5.2
 * Requires PHP:      		7.0
 * WC requires at least:	3.0
 * WC tested up to:      	8.1.1
 * Author:            		Marcos Gomes Neto
 * Author URI:        		https://github.com/marcosgomesneto
 * Text Domain:       		s3-media-upload
 * License:           		GPLv2 or later
 * License URI:       		http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined('ABSPATH') || exit;

//Define globals
define('S3_MEDIA_UPLOAD_PLUGIN_NAME', 's3-media-upload');
define('S3_MEDIA_UPLOAD_I18N_NAME', 's3-media-upload');
define('S3_MEDIA_UPLOAD_PLUGIN_VERSION', '1.0.0');
define('S3_MEDIA_UPLOAD_PLUGIN_URL', plugin_dir_url(__FILE__));
define('S3_MEDIA_UPLOAD_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('S3_MEDIA_UPLOAD_BASE_NAME', plugin_basename(__FILE__));
define('S3_MEDIA_UPLOAD_DIR_NAME', dirname(plugin_basename(__FILE__)));

require S3_MEDIA_UPLOAD_PLUGIN_PATH . 'vendor/autoload.php';

/**
 * Global function-holder. Works similar to a singleton's instance().
 *
 * @since 1.0.0
 *
 * @return S3MediaUpload\Core
 */
function s3_media_upload_init()
{
    /**
     * @var \S3MediaUpload\Core
     */
    static $core;

    if (!isset($core)) {
        $core = new \S3MediaUpload\Core();
    }

    return $core;
}

s3_media_upload_init();
