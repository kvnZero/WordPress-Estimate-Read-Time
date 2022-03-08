<?php
/*
Plugin Name: Estimate Read Time
Plugin URI: https://github.com/kvnZero/WordPress-Estimate-Read-Time
Description: when save post, this plugin will estimate read time and save in post meta
Version: 1.1
Author: abigeater
Author URI: https://abigeater.com
*/

define('AB_READ_TIME_PLUGIN_URL', plugins_url('', __FILE__));
define('AB_READ_TIME_PLUGIN_DIR', plugin_dir_path(__FILE__));

if (is_admin()) {
	include AB_READ_TIME_PLUGIN_DIR . 'admin/admin.php';

	include AB_READ_TIME_PLUGIN_DIR . 'public/hooks.php';
}