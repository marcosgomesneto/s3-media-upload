<?php

namespace AwsServicesKit\Config\Configs;

use AwsServicesKit\Config\AbstractConfig;

defined( 'ABSPATH' ) || exit;

class GeneralConfig extends AbstractConfig {

	public $access_key_id;
	public $secret_access_key;
	public $region;
	public $basedir;

}
