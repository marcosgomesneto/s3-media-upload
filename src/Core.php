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
        add_action('woocommerce_after_product_object_save', [$this, 'after_product_save']);

        //Replace attachment URLs requests
        add_filter('wp_get_attachment_url', [$this, 'get_attachment_url'], 100, 2);
        add_filter('wp_calculate_image_srcset', [$this, 'wp_calculate_image_srcset'], 10, 5);
        //add_filter('wp_get_attachment_image_attributes', [$this, 'get_attachment_image_attributes'], 100, 3);

        // Srcset handling
        add_filter('wp_image_file_matches_image_meta', [$this, 'image_file_matches_image_meta'], 10, 4);

        add_filter('wp_generate_attachment_metadata', [$this, 'generate_attachment_metadata'], 100, 3);
        add_filter('plugin_action_links_' . \S3_MEDIA_UPLOAD_BASE_NAME, [$this, 'plugin_action_links']);
    }

    /**
     * Before product save
     *
     * @param WC_Product $product
     * @return void
     */
    public function after_product_save($product)
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
        if ($this->currentProduct == $product->get_id() || !$product->is_downloadable())
            return;

        $this->currentProduct = $product->get_id();

        $uploaded = get_post_meta($this->currentProduct, '_s3mu_uploaded', true);

        if ($uploaded == 'yes') return;

        $wp_upload_dir = wp_upload_dir();

        $downloads = $product->get_downloads();
        if (empty($downloads)) return;

        $download = end($downloads);
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

        $config = Config::get();

        if ($config->remove_local) {
            unlink($local_file_path);
        }
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

            $relative_dir = dirname($metadata['file']);

            if (!empty($config->include_folders)) {
                if (!in_array($relative_dir, $config->include_folders)) {
                    return $metadata;
                }
            }

            $s3 = S3Service::getInstance();
            $original_file = $config->basedir . '/' .  $metadata['file'];
            $wp_upload_dir = wp_upload_dir();


            $files_to_remove = [];

            if (!in_array($original_file, $uploads)) {
                $local_file_path = $wp_upload_dir['basedir'] . '/' . $metadata['file'];
                $s3->uploadFile($local_file_path);
                array_push($uploads, $original_file);
                array_push($files_to_remove, $local_file_path);
            }

            foreach ($metadata['sizes'] as $size) {
                $local_file_path = $wp_upload_dir['basedir'] . '/' . $relative_dir . '/' . $size['file'];
                $s3->uploadFile($local_file_path);
                array_push($uploads, $config->basedir . '/' . $relative_dir .  '/' . $size['file']);
                array_push($files_to_remove, $local_file_path);
            }


            update_post_meta($attachment_id, '_s3mu_uploads', $uploads);
            update_post_meta($attachment_id, '_s3mu_status', 'success');
            update_post_meta($attachment_id, '_s3mu_uploaded', 'yes');
            ////file_put_contents(\S3_MEDIA_UPLOAD_PLUGIN_PATH . "log.txt", "Removing local\r\n", FILE_APPEND);
            if ($config->remove_local) {
                foreach ($files_to_remove as $file) {
                    unlink($file);
                }
            }
        } catch (\Exception $e) {
            update_post_meta($attachment_id, '_s3mu_status', 'fail');
        }
        return $metadata;
    }

    /**
     * Replace local URLs with provider ones for srcset image sources.
     *
     * @param array  $sources
     * @param array  $size_array
     * @param string $image_src
     * @param array  $image_meta
     * @param int    $attachment_id
     *
     * @return array
     */
    public function wp_calculate_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id)
    {
        $uploaded = get_post_meta($attachment_id, '_s3mu_uploaded', true);

        if ($uploaded !== 'yes') return $sources;

        $upload_dir = wp_upload_dir();
        $s3 = S3Service::getInstance();

        foreach ($sources as $width => $source) {
            $base_upload = $upload_dir['baseurl'];
            $remote_url = str_replace($base_upload, '', $source['url']);

            $sources[$width]['url'] = $s3->getBaseUrl() . $remote_url;
        }

        return $sources;
    }
}
