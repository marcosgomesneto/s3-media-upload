<?php
/**
 * @var AwsServicesKit\Admin\Settings\FaceRecognitionSettings $this
 */
use Aws\Rekognition;

defined( 'ABSPATH' ) || exit;
?>

<div class="wrap">
	<form id="aws-rek" method="post">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<?php esc_html_e( 'Ative', \S3_MEDIA_UPLOAD_I18N_NAME ); ?>
					</th>
					<td>
						<fieldset>
							<input name="awsrek_active" type="checkbox" id="awsrek_active" <?php echo esc_attr( $options->enabled == 1 ? 'checked' : '' ); ?>>
							<label for="awsrek_active">Active Woocommerce Rekognition Search</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php esc_html_e( 'Collection', \S3_MEDIA_UPLOAD_I18N_NAME ); ?>
					</th>

					<td>
						<select name="awsrek_collection" id="awsrek_collection">
							<option value="-1" selected>Selecione</option>
							<?php
							foreach ( $collections as $collectionId ) {
								echo sprintf(
									'<option value="%s" %s>%s</option>',
									$collectionId, $collectionId == $options->collection ? 'selected' : '',
									$collectionId
								);
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php esc_html_e( 'Search Form Style', \S3_MEDIA_UPLOAD_I18N_NAME ); ?>
					</th>

					<td>
						<select name="awsrek_form_theme" id="awsrek_form_theme">
							<?php
							foreach ( $form_themes as $key => $value ) {
								echo sprintf(
									'<option value="%s" %s>%s</option>',
									$key, $key == $options->form_theme ? 'selected' : '',
									$value
								);
							}
							?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field( $this->tab, $this->tab ); ?>
		<p class="submit">
			<?php submit_button( esc_html__( 'Save', \S3_MEDIA_UPLOAD_I18N_NAME ), 'primary', 'submit', false ); ?>
		</p>
	</form>
</div>