<?php

namespace S3MediaUpload;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Exception;
use S3MediaUpload\Admin\Admin;
use S3MediaUpload\Admin\S3MediaUploadAdmin;
use S3MediaUpload\Services\S3Service;
use S3MediaUpload\Utils\Config;

defined('ABSPATH') || exit;

class Core
{
    /**
     * Current product in process
     *  
     * @var int|null
     */
    private $currentProduct = null;

    public function __construct()
    {

        new Admin();
        add_action('woocommerce_after_product_object_save', [$this, 'before_product_save']);
        //add_filter('upload_dir', [$this, 'upload_dir']);

        //Replace attachment URLs requests
        add_filter('wp_get_attachment_url', [$this, 'get_attachment_url'], 100, 2);
        add_filter('wp_get_attachment_image_attributes', [$this, 'get_attachment_image_attributes'], 100, 3);


        add_filter('wp_generate_attachment_metadata', [$this, 'generate_attachment_metadata'], 100, 3);
        /*         add_action('add_attachment', [$this, 'add_attachment']);
        
        add_filter('update_attached_file', [$this, 'update_attached_file'], 10, 2);
        add_filter('wp_update_attachment_metadata', [$this, 'update_attachment_metadata'], 10, 2); */
        add_filter('plugin_action_links_' . \S3_MEDIA_UPLOAD_BASE_NAME, [$this, 'plugin_action_links']);
    }

    /**
     * Before product save
     *
     * @param WC_Product $product
     * @return void
     */
    public function before_product_save($product)
    {
        try {
            $this->upload($product);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * Upload image download to S3 Bucket
     *
     * @param WC_Product $product 
     * @return void
     */
    private function upload($product)
    {
        if ($this->currentProduct == $product->get_id())
            return;

        $this->currentProduct = $product->get_id();

        $uploaded = get_post_meta($this->currentProduct, '_s3mu_uploaded', true);

        if ($uploaded == 'yes') return;

        $wp_upload_dir = wp_upload_dir();
        $download = end($product->get_downloads());
        $uploadUrl = $wp_upload_dir['baseurl'];
        $currentFilePath = str_replace($uploadUrl, '', $download['file']);
        $local_file_path = $wp_upload_dir['basedir'] . $currentFilePath;

        try {
            $s3 = S3Service::getInstance();
            $result = $s3->uploadFile($local_file_path);
            $this->save($product, $result['ObjectURL'], $download['name']);
        } catch (S3Exception $e) {
            throw new Exception($e->getMessage());
        }

        update_post_meta($this->currentProduct, '_s3mu_uploaded', 'yes');
        unlink($local_file_path);
    }

    /**
     * Save product with new download file
     * 
     * @param WC_Product $product 
     * @param string $fileUrl
     * @param string $name
     * @return void
     */
    private function save($product, $fileUrl, $name)
    {
        $downloads[] = $this->createDownload($fileUrl, $name);
        $product->set_downloads($downloads);
        if (!$product->save()) {
            throw new Exception("error saving product");
        }
    }

    private function createDownload($file, $name)
    {
        try {
            $download = new \WC_Product_Download();
            $download->set_name($name);
            $download->set_id(md5($file));
            $download->set_file($file);
            return $download;
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ' ' . $ex->getMessage());
        }
    }

    /**
     * Plugin actions links shortcut
     *
     * @since 1.0.0
     * @return void
     */
    public function plugin_action_links($links)
    {
        $pluginLinks = array();

        $baseUrl = esc_url(admin_url('admin.php?page=s3-media-upload'));

        $pluginLinks[] = sprintf('<a href="%s">%s</a>', $baseUrl, __('Configurations', \S3_MEDIA_UPLOAD_I18N_NAME));

        return array_merge($pluginLinks, $links);
    }

    /**
     * Update attached file hook
     *
     * @param string $file Path to the attached file to update.
     * @param int $attachment_id
     * @return void
     */
    public function update_attached_file($file, $attachment_id)
    {

        //throw new Exception('Update atached file' . $file . $attachment_id);
        return $file;
    }

    /**
     * Update attachment metadata
     *
     * @param array $data
     * @param int $post_id
     * @return void
     */
    public function update_attachment_metadata($data, $post_id)
    {

        //echo "\r\n--------" . print_r($data, true);
        return $data;
    }

    public function add_attachment($post_id)
    {
        /* $metadata = wp_get_attachment_metadata($post_id);
        $file = get_attached_file($post_id);
        echo "\r\n-----meta---" . $file . '---' . print_r($metadata, true) . '-' . $post_id; */
    }

    /*     public function upload_dir($upload_dir)
    {
        $config = Config::get();
        $bucket_name = $config->bucket_name;
        $region = $config->region;
        if (empty($bucket_name) || empty($region)) return $upload_dir;
        $upload_dir['baseurl'] = "https://$bucket_name.s3.$region.amazonaws.com/uploads";
        return $upload_dir;
    } */


    /**
     * Get url attachment
     *
     * @param string $url
     * @param int    $post_id
     * @return void
     */
    public function get_attachment_url($url, $attachment_id)
    {
        $uploaded = get_post_meta($attachment_id, '_s3mu_uploaded', true);

        if ($uploaded !== 'yes') return $url;

        $upload_dir = wp_upload_dir();
        $path = str_replace($upload_dir['baseurl'], '', $url);
        $s3 = S3Service::getInstance();
        return $s3->getBaseUrl() . $path;
    }


    /**
     * Filters the list of attachment image attributes.
     *
     * @param array        $attr       Attributes for the image markup.
     * @param WP_Post      $attachment Image attachment post.
     * @param string|array $size       Requested size. Image size or array of width and height values (in that order).
     *
     * @return array
     */
    public function get_attachment_image_attributes($attr, $attachment, $size)
    {
        $uploaded = get_post_meta($attachment->ID, '_s3mu_uploaded', true);

        if ($uploaded !== 'yes') return $attr;

        if (!empty($attr['src'])) {
            $upload_dir = wp_upload_dir();
            $path = str_replace($upload_dir['baseurl'], '', $attr['src']);
            $s3 = S3Service::getInstance();
            $attr['src'] = $s3->getBaseUrl() . $path;
        }

        return $attr;
    }

    /**
     * Meta data
     *
     * @since 1.0.0
     * @param array  $metadata
     * @param int    $attachment_id
     * @param string $action 'create'
     * @return array
     */
    public function generate_attachment_metadata($metadata, $attachment_id, $action)
    {
        try {
            $uploads = get_post_meta($attachment_id, '_s3mu_uploads');
            $config = Config::get();
            $s3 = S3Service::getInstance();
            $original_file = $config->basedir . '/' .  $metadata['file'];
            $wp_upload_dir = wp_upload_dir();
            $relative_dir = dirname($metadata['file']);

            if (!in_array($original_file, $uploads)) {
                $local_file_path = $wp_upload_dir['basedir'] . '/' . $metadata['file'];
                $s3->uploadFile($local_file_path);
                array_push($uploads, $original_file);
            }

            foreach ($metadata['sizes'] as $size) {
                $s3->uploadFile($wp_upload_dir['basedir'] . '/' . $relative_dir . '/' . $size['file']);
                array_push($uploads, $config->basedir . '/' . $relative_dir .  '/' . $size['file']);
            }


            update_post_meta($attachment_id, '_s3mu_uploads', $uploads);
            update_post_meta($attachment_id, '_s3mu_status', 'success');
            update_post_meta($attachment_id, '_s3mu_uploaded', 'yes');
        } catch (\Exception $e) {
            update_post_meta($attachment_id, '_s3mu_status', 'fail');
        }
        return $metadata;
    }
}
