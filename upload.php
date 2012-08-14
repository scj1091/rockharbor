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

$theme = new $class;

// go through WordPress' API to pass off authentication responsibilities
$client = new IXR_Client(get_option('home').'/xmlrpc.php');

// new filename
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
	$response = array(
		'errors' => array('Invalid file type'),
		'message' => 'Only messages (video and audio) are supported.'
	);
	echo json_encode($response);
	exit;
}

// upload the file
$postdata = array(
	'name' => $_FILES['file']['name'],
	'type' => $type,
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