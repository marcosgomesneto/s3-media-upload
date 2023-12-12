<?php

namespace AwsServicesKit\Admin;

use AwsServicesKit\Admin\Settings\AwsRekognitionSettings;
use AwsServicesKit\Admin\Settings\FaceRecognitionSettings;
use AwsServicesKit\Admin\Settings\GeneralSettings;
use AwsServicesKit\Admin\Settings\Settings;
use AwsServicesKit\Admin\Settings\S3UploadSettings;

defined( 'ABSPATH' ) || exit;


class Admin {


	public function __construct() {
		new View();
		add_action( 'wp_ajax_awskit_upload_search_image', [ $this, 'upload_search_image' ] );
		add_action( 'wp_ajax_nopriv_awskit_upload_search_image', [ $this, 'upload_search_image' ] );
	}

	public function upload_search_image() {
		$upload_dir = wp_upload_dir();
		$upload_path = $upload_dir['basedir'] . '/awskit/user-search/';
		$upload_url = $upload_dir['baseurl'] . '/awskit/user-search/';
		if ( ! file_exists( $upload_path ) ) {
			wp_mkdir_p( $upload_path );
		}
		$filename = basename( $_FILES['awskitFileUpload']['name'] );
		$upload_file = $upload_path . $filename;
		$upload_url_file = $upload_url . $filename;

		$counter = 1;
		$file_ext = pathinfo( $upload_file, PATHINFO_EXTENSION );
		$file_name_without_ext = pathinfo( $upload_file, PATHINFO_FILENAME );
		while ( file_exists( $upload_file ) ) {
			$new_filename = $file_name_without_ext . '-' . $counter;
			$upload_file = $upload_path . $new_filename . '.' . $file_ext;
			$upload_url_file = $upload_url . $new_filename . '.' . $file_ext;
			$counter++;
		}

		if ( move_uploaded_file( $_FILES['awskitFileUpload']['tmp_name'], $upload_file ) ) {
			$redirect_url = get_site_url() . '/?s=&post_type=product&awskit_image=' . $upload_file;

			wp_send_json( [ 'success' => true, 'redirect_url' => $redirect_url ] );

		} else {
			wp_send_json( [ 'success' => false ] );
		}
		wp_die();
	}


}
