<?php
/**
 * This file is a publicly accessible end-point to uploading a file using the
 * WordPress XMLRPC API, thereby calling all hooks and filters associated with
 * it.
 * 
 * To call the sync, you *must* POST a request with the raw upload data and
 * include the following data:
 * 
 * file=<file>
 * &username=<wordpress_username>
 * &password=<wordpress_password>
 */

/**
 * Sends json response
 * 
 * @param string $message
 * @param array $errors 
 */
function respond($message, $errors = array()) {
	header("Content-type: application/json");
	header("Connection: close");
	$response = compact('message');
	if (!empty($errors)) {
		$response['errors'] = (array)$errors;
	}
	echo json_encode($response);
	exit;
}

$code = '';
$code .= empty($_FILES) ? '1' : '0';
$code .= empty($_FILES['file']) ? '1' : '0';
$code .= empty($_POST['username']) ? '1' : '0';
$code .= empty($_POST['password']) ? '1' : '0';
if ($code !== '0000') {
	$errors = array(
		$code.': Invalid POST data'
	);
	respond('Please leave. KTHXBYE.', $errors);
}

/**
 * Load wordpress
 */
require_once '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'wp-load.php';
include_once ABSPATH . 'wp-admin/includes/admin.php';

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

global $wpdb;
$theme = new $class;

$message = 'Error, could not upload.';

// login
$username = $wpdb->escape($_POST['username']);
$password = $wpdb->escape($_POST['password']);
$user = wp_authenticate($username, $password);
if (is_wp_error($user)) {
	respond($message, 'Invalid authorization key');
}

// check permissions
wp_set_current_user($user->ID);
if (!current_user_can('upload_files')) {
	respond($message, 'Insufficient permissions.');
}

// allow upload process to go for a while
set_time_limit(3600);

// prepare file
$fileparts = explode('.', $_FILES['file']['name']);
$ext = array_pop($fileparts);

// sometimes curl doesn't get the mime type right. I'm trusting the client here
// since it's us who's encoding it
$type = null;
switch ($ext) {
	case 'mp4':
	case 'mov':
	case 'mpeg':
	case 'mpeg4':
		$type = 'video/mp4';
	break;
	case 'mp3':
		$type = 'audio/mp3';
	break;
}

// unsupported at this time. We only want messages
if ($type === null) {
	respond('Only messages (video and audio) are supported.', 'Invalid file type');
}

$uploadError = apply_filters('pre_upload_error', false);
if ($uploadError) {
	respond($message, $uploadError);
}

// trick WP into thinking this is a form submission (really? this is their check?)
$_POST['action'] = 'wp_handle_upload';
// upload
$id = media_handle_upload('file', 0);
$errors = array();
if (!is_wp_error($id)) {
	$message = $_FILES['file']['name'].' successfully added to '.$theme->info('name').' media library';
} else {
	$errors[] = $id->get_error_message();
}

respond($message, $errors);