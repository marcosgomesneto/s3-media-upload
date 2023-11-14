<?php

namespace S3MediaUpload\Admin;

defined('ABSPATH') || exit;


class Admin
{
    public $template_path;

    public function __construct()
    {
        $this->template_path = S3_MEDIA_UPLOAD_PLUGIN_PATH . 'src/Admin/templates/';
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    public function admin_menu()
    {
        add_submenu_page(
            'tools.php',
            'S3 Media Upload',
            'S3 Media Upload',
            'manage_options',
            's3-media-upload',
            [$this, 'render_html']
        );
    }

    public function render_html()
    {
        if (isset($_POST['s3_media_upload_button_form_nonce']) && check_admin_referer('s3_media_upload_save_button', 's3_media_upload_button_form_nonce')) {
            $options['bucket_name'] = sanitize_text_field($_POST['bucket_name']);
            $options['access_key_id'] = sanitize_text_field($_POST['access_key_id']);
            $options['secret_access_key'] = sanitize_text_field($_POST['secret_access_key']);
            $options['region'] = sanitize_text_field($_POST['region']);
            $options['remove_local'] = $_POST['remove_local'] ? true : false;
            $options['include_folders'] = sanitize_textarea_field($_POST['include_folders']);

            update_option(
                's3_media_upload_options',
                $options
            );
        }
        $options = get_option('s3_media_upload_options');
        include_once($this->template_path . 'config-page.php');
    }
}
