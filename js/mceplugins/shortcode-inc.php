<?php

global $wpdb;

// load wordpress
if (!defined('WP_LOAD_PATH')) {
/** classic root path if wp-content and plugins is below wp-config.php */
$classic_root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/' ;

	if (file_exists( $classic_root . 'wp-load.php')) {
		define('WP_LOAD_PATH', $classic_root);
	} else {
		if (file_exists($path . 'wp-load.php')) {
			define('WP_LOAD_PATH', $path);
		} else {
			exit('Could not find wp-load.php');
		}
	}
}

// let's load WordPress
require_once( WP_LOAD_PATH . 'wp-load.php');

// check for rights
if (!is_user_logged_in() || !current_user_can('edit_posts')) {
	wp_die(__('You are not allowed to be here'));
}
?>