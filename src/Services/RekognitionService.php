<?php

namespace AwsServicesKit\Services;

use Aws\Rekognition\RekognitionClient;
use AwsServicesKit\Config\ConfigLoader;

defined( 'ABSPATH' ) || exit;

class RekognitionService {
	/**
	 * Singleton instance
	 *
	 * @var RekognitionService
	 */
	private static $instance;

	/**
	 * S3 Instance API Client
	 *
	 * @var RekognitionClient
	 */
	private $rekognition;

	/**
	 * Bucket region in amazon s3
	 *
	 * @var string
	 */
	private $region;

	public function __construct() {
		$config = ConfigLoader::get();
		$this->region = $config->general->region;

		$this->rekognition = new RekognitionClient( [ 
			'version' => 'latest',
			'region' => $config->general->region,
			'credentials' => [ 
				'key' => $config->general->access_key_id,
				'secret' => $config->general->secret_access_key
			],
		] );
	}

	/**
	 * Get collections ids
	 *
	 * @return array
	 */
	public function getCollectionsIds() {
		$collections = $this->rekognition->listCollections();
		return $collections['CollectionIds'];
	}

	/**
	 * Index faces
	 *
	 * @param string $collection
	 * @param string $product_id
	 * @return void
	 */

	/**
	 * Index Faces
	 *
	 * @param string $collection
	 * @param string $product_id
	 * @param string $filename
	 * @return void
	 */
	public function indexFaces( $collection, $product_id, $filename ) {
		$config = ConfigLoader::get();

		$this->rekognition->indexFaces( [ 
			'CollectionId' => $collection,
			'ExternalImageId' => (string) $product_id,
			"Image" => [ 
				"S3Object" => [ 
					"Bucket" => $config->s3_upload->bucket_name,
					"Name" => $filename,
				]
			]
		] );
	}

	/**
	 * Search faces by image
	 *
	 * @param string $image_path
	 * @return array
	 */
	public function searchFacesByImage( $image_path ) {
		$config = ConfigLoader::get();

		if ( ! file_exists( $image_path ) )
			throw new \Exception( 'File not found' );


		$image_blob = file_get_contents( $image_path );

		$result = $this->rekognition->searchFacesByImage( [ 
			'CollectionId' => $config->face_recognition->collection,
			'Image' => [ 
				'Bytes' => $image_blob,
			]
		] );

		if ( empty( $result['FaceMatches'] ) ) {
			throw new \Exception( 'No faces found' );
		}

		$productsIds = [];
		foreach ( $result['FaceMatches'] as $face ) {
			$productsIds[] = $face['Face']['ExternalImageId'];
		}

		return $productsIds;
	}

	public static function getInstance() {
		if ( self::$instance === null ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
}
