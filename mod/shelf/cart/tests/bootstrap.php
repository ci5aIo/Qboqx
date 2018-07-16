<?php

date_default_timezone_set('Europe/Berlin');

error_reporting(E_ALL | E_STRICT);

global $CONFIG;
$CONFIG = (object) [
			'dbprefix' => 'elgg_',
			'boot_complete' => false,
			'wwwroot' => 'http://localhost/',
			'site_guid' => 1,
];

$plugin_root = dirname(dirname(__FILE__));

$libs = array(
	dirname(dirname($plugin_root)) . '/engine/load.php',
	dirname(dirname($plugin_root)) . '/vendor/autoload.php',
	dirname(dirname($plugin_root)) . '/mod/payments/autoloader.php',
	$plugin_root . '/autoloader.php',
);

foreach ($libs as $lib) {
	require_once $lib;
}
