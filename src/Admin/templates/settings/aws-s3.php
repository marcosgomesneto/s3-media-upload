<?php
/**
 * @var AwsServicesKit\Admin\Settings\S3UploadSettings $this
 */
defined( 'ABSPATH' ) || exit;
?>
<form id="aws_services_kit" method="post">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Bucket Name', \S3_MEDIA_UPLOAD_I18N_NAME ); ?>
				</th>
				<td>
					<input name="bucket_name" type="text" id="bucket_name"
						value="<?php echo esc_attr( $options->bucket_name ); ?>" class="regular-text">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Remove Local Files', \S3_MEDIA_UPLOAD_I18N_NAME ); ?>
				</th>
				<td>
					<fieldset>

						<input name="remove_local" type="checkbox" id="remove_local" <?php echo esc_attr( $options->remove_local == 1 ? 'checked' : '' ); ?>>
						<label>Remove local files when uploaded in S3</label>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Include upload folders', \S3_MEDIA_UPLOAD_I18N_NAME ); ?>
				</th>
				<td>
					<fieldset>
						<textarea style="height: 200px;" class="regular-text code" name="include_folders"
							id="include_folders"
							placeholder="Blank for all folders in upload folder"><?php echo esc_attr( implode( PHP_EOL, $options->include_folders ) ); ?></textarea>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	<?php wp_nonce_field( $this->getTab(), $this->getTab() ); ?>
	<p class="submit">
		<?php submit_button( esc_html__( 'Save', \S3_MEDIA_UPLOAD_I18N_NAME ), 'primary', 'submit', false ); ?>
	</p>
</form>