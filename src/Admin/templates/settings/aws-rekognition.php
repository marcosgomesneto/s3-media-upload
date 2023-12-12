<?php
/**
 * @var AwsServicesKit\Admin\Settings\AwsRekognitionSettings $this
 */
defined( 'ABSPATH' ) || exit;
?>
<style>
	.aws-services-kit-option-wrap>a {
		display: inline-block;
		text-decoration: none;
	}

	.aws-services-kit-option-wrap>a:active,
	.aws-services-kit-option-wrap>a:focus,
	.aws-services-kit-option-wrap>a:hover {
		outline: none;
		box-shadow: none;
	}

	.aws-services-kit-option-box {
		width: 255px;
		height: 255px;
		background-color: #fff;
		display: flex;
		flex-direction: column;
		border: 1px solid #D8D8D8;
		border-radius: 5px;
		overflow: hidden;
	}

	.aws-services-kit-option-image {
		flex: 1;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.aws-services-kit-option-title {
		background-color: #6E95EF;
		color: #fff;
		padding: 1rem;
		text-align: center;
	}
</style>
<div class="wrap aws-services-kit-option-wrap">
	<a
		href="<?php echo add_query_arg( [ 'tab' => $this->getTab(), 'section' => 'woocommerce-product-search' ], \S3_MEDIA_UPLOAD_SETTINGS_BASE_URL ); ?>">
		<div class="aws-services-kit-option-box">
			<div class="aws-services-kit-option-image">
				<img
					src="<?php echo sprintf( "%simages/face-recognition-settings.jpg", \S3_MEDIA_UPLOAD_PLUGIN_ASSETS_URL ); ?>" />
			</div>
			<div class="aws-services-kit-option-title">
				<?php _e( 'WooCommerce Product Search by Face Recognition', \S3_MEDIA_UPLOAD_I18N_NAME ); ?>
			</div>
		</div>
	</a>
</div>