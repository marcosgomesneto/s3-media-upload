<div class="wrap">
    <h1>
        Configurations
    </h1>
    <form id="s3_media_upload" method="post" action="tools.php?page=s3-media-upload">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php esc_html_e('Bucket Name', \S3_MEDIA_UPLOAD_I18N_NAME); ?></th>
                    <td>
                        <input name="bucket_name" type="text" id="bucket_name" value="<?php echo esc_attr($options["bucket_name"]); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Access Key ID', \S3_MEDIA_UPLOAD_I18N_NAME); ?></th>
                    <td>
                        <input name="access_key_id" type="text" id="access_key_id" value="<?php echo esc_attr($options["access_key_id"]); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Secret Access Key', \S3_MEDIA_UPLOAD_I18N_NAME); ?></th>
                    <td>
                        <input name="secret_access_key" type="text" id="secret_access_key" value="<?php echo esc_attr($options["secret_access_key"]); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Region', \S3_MEDIA_UPLOAD_I18N_NAME); ?></th>
                    <td>
                        <input name="region" type="text" id="region" value="<?php echo esc_attr($options["region"]); ?>" class="regular-text">
                    </td>
                </tr>
            </tbody>
        </table>
        <?php wp_nonce_field('s3_media_upload_save_button', 's3_media_upload_button_form_nonce'); ?>
        <p class="submit">
            <?php submit_button(esc_html__('Save', \S3_MEDIA_UPLOAD_I18N_NAME), 'primary', 'submit', false); ?>
        </p>
    </form>
</div>