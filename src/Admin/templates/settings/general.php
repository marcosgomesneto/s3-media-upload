<?php
/**
 * @var AwsServicesKit\Admin\Settings\GeneralSettings $this
 */
defined( 'ABSPATH' ) || exit;
?>
<form id="aws_services_kit" method="post">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Access Key ID', \S3_MEDIA_UPLOAD_I18N_NAME ); ?>
				</th>
				<td>
					<input name="access_key_id" type="text" id="access_key_id"
						value="<?php echo esc_attr( $options->access_key_id ); ?>" class="regular-text">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Secret Access Key', \S3_MEDIA_UPLOAD_I18N_NAME ); ?>
				</th>
				<td>
					<input name="secret_access_key" type="text" id="secret_access_key"
						value="<?php echo esc_attr( $options->secret_access_key ); ?>" class="regular-text">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Region', \S3_MEDIA_UPLOAD_I18N_NAME ); ?>
				</th>
				<td>
					<input name="region" type="text" id="region" value="<?php echo esc_attr( $options->region ); ?>"
						class="regular-text">
				</td>
			</tr>
		</tbody>
	</table>
	<?php wp_nonce_field( $this->getTab(), $this->getTab() ); ?>
	<p class="submit">
		<?php submit_button( esc_html__( 'Save', \S3_MEDIA_UPLOAD_I18N_NAME ), 'primary', 'submit', false ); ?>
	</p>
</form>