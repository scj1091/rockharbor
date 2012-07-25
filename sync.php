<?php
header("Content-type: application/json");

/**
 * This file is a publicly accessible end-point to copying files from a subsite's
 * Amazon bucket (<bucket>/messages) to the correct upload location within
 * WordPress *and* creating a media library record for that item.
 * 
 * To call the sync, you *must* POST a request with the following data:
 * 
 * key=<your_key_defined_in_theme_options>
 * &secret_key=sha1(<your_key_defined_in_theme_options>.AUTH_SALT)
 * &file=<object_filename_in_messages_folder>
 */
if (empty($_POST) 
	|| empty($_POST['key']) 
	|| empty($_POST['secret_key']) 
	|| empty($_POST['file'])
	) {
	$response = array(
		'errors' => array(
			'Invalid authorization key',
			'Invalid POST data'
		),
		'message' => 'Please leave. KTHXBYE.'
	);
	echo json_encode($response);
	exit;
}

/**
 * Load wordpress
 */
require_once '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'wp-config.php';

/**
 * Load theme base
 */
require_once rtrim(get_template_directory(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'rockharbor_theme_base.php';
$class = 'RockharborThemeBase';
$file = rtrim(get_stylesheet_directory(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'child_theme.php';
if (file_exists($file)) {
	require_once $file;
	$class = 'ChildTheme';
}

$theme = new $class;

// check key and secret key
$key = $theme->options('media_sync_key');
$secret = sha1($key.AUTH_SALT);

// check authorization keys
if ($_POST['key'] != $key || $_POST['secret_key'] != $secret) {
	$response = array(
		'errors' => array('Invalid authorization key.'),
		'message' => 'Please leave. KTHXBYE.'
	);
	echo json_encode($response);
	exit;
}

include_once $theme->info('base_path') . DS . 'vendors' . DS . 'S3.php';
		
if (!class_exists('S3')) {
	$response = array(
		'errors' => array('Missing Amazon S3 class.'),
		'message' => 'Please contact the system administrator.'
	);
	echo json_encode($response);
	exit;
}

$s3options = get_option('tantan_wordpress_s3');
$storage = new S3($s3options['key'], $s3options['secret']);
S3::$useExceptions = true;
$bucket = $s3options['bucket'];

/**
 * Okay, so the current TanTanS3 plugin stores items differently depending
 * on if this is a subsite or not, so we'll need to use their "method"
 * to find the new path.
 * 
 * This *needs* to be cleaned up (see #11)
 */
include_once WP_PLUGIN_DIR.DS.'tantan-s3-cloudfront'.DS.'wordpress-s3'.DS.'class-plugin.php';
$tanTan = new TanTanWordPressS3Plugin();
add_filter('option_siteurl', array($tanTan, 'upload_path'));
$uploadDir = wp_upload_dir();
remove_filter('option_siteurl', array($tanTan, 'upload_path'));
$parts = parse_url($uploadDir['url']);
$prefix = substr($parts['path'], 1) .'/';

$errors = array();
$message = 'No changes.';

$object = $_POST['file'];
// copy to proper wp uploads path
$success = false;
try {
	$success = $storage->copyObject($bucket, 'messages/'.$object, $bucket, $prefix.$object, S3::ACL_PUBLIC_READ);
} catch (S3Exception $e) {
	$errors[] = $e->getMessage();
}
if ($success) {
	$explode = explode('.', $object);
	$ext = array_pop($explode);
	switch ($ext) {
		case 'mp3':
			$mime = 'audio/mp3';
		break;
		default:
			$mime = 'video/mp4';
		break;
	}
	// save to wp db
	$attachment = array(
		'post_mime_type' => $mime, // hardcode for now
		'guid' => get_site_url()."/$prefix$object",
		'post_title' => $object,
		'post_name' => $object,
		'post_date' => current_time('mysql'),
		'post_date_gmt' => current_time('mysql', 1)
	);
	$id = wp_insert_attachment($attachment);
	if ($id > 0) {
		// delete from old path
		try {
			$storage->deleteObject($bucket, 'messages/'.$object);
		} catch (S3Exception $e) {
			$errors[] = $e->getMessage();
		}
		// tan tan junk
		delete_post_meta($id, 'amazonS3_info');
		add_post_meta($id, 'amazonS3_info', array(
			'bucket' => $bucket,
			'key' => $prefix.$object
		));
	}
	$message = "$object added to the media library.";
}

$response = compact('errors', 'message');

echo json_encode($response);