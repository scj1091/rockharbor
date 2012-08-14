<?php
header("Content-type: application/json");

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

if (empty($_POST) 
	|| empty($_FILES)
	|| empty($_POST['username']) 
	|| empty($_POST['password']) 
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
 * Load special classes 
 */
require_once ABSPATH . 'wp-includes' . DIRECTORY_SEPARATOR . 'class-IXR.php';

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

// go through WordPress' API to pass off authentication responsibilities
$client = new IXR_Client(get_option('home').'/xmlrpc.php');

// new filename
$fileparts = explode('.', $_FILES['file']['name']);
$ext = array_pop($fileparts);

// upload the file
$postdata = array(
	'name' => $_FILES['file']['name'],
	'type' => $_FILES['file']['type'],
	'bits' => new IXR_Base64(file_get_contents($_FILES['file']['tmp_name'])),
	'overwrite' => 0
);
$rep = $client->query('wp.uploadFile', $theme->info('id'), $_POST['username'], $_POST['password'], $postdata);

if (!$rep) {
	$response = array(
		'errors' => $client->getErrorCode(),
		'message' => $client->getErrorMessage()
	);
	echo json_encode($response);
	exit;
}

$response = array(
	'errors' => array(),
	'message' => 'File successfully added to '.$theme->info('name').' media library'
);
echo json_encode($response);
exit;