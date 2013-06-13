<?php
/**
 * Facilitates downloading a file
 */

if (!array_key_exists('file', $_GET) || !array_key_exists('filename', $_GET)) {
	header('Location: '.$_SERVER['HTTP_REFERER']);
	exit;
}

$file = urldecode($_GET['file']);
$filename = $_GET['filename'];
$headers = get_headers($file, 1);
$mimetype = $headers['Content-Type'];

header("Content-type: $mimetype");
header("Content-disposition: attachment; filename=\"$filename\"");

readfile($file);