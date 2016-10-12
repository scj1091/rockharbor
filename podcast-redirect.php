<?php
// We're hardcoding this. Yes, that's bad. But not as bad as trying to pull it from the WP theme :-(
$gaProperty = 'UA-7415608-7';

$url = isset($_GET['url']) ? $_GET['url'] : '';
$key = isset($_GET['key']) ? $_GET['key'] : '';

// If no URL parameter, or no HMAC key parameter, don't redirect
// We don't want any open redirects
if (is_null($url) || is_null($key) || $url == '' || $key == '') {
	http_response_code(400);
	echo "<span style=\"font-family: Arial, sans-serif; font-size: 40px;\">Invalid link.</span>";
	exit();
}

// If URL doesn't match HMAC hash, don't redirect
// We're also hard-coding this secret key too. Also bad. Sue me.
$expectedHash = hash_hmac('sha256', urlencode($url), "9b01b2733c2d4e7f8c0d8effa8da30bd");
if (md5($expectedHash) != md5($key)) {
	http_response_code(400);
	echo "<span style=\"font-family: Arial, sans-serif; font-size: 40px;\">Invalid link.</span>";
	exit();
}

// If HMAC key matches URL, either renew existing cookie or set a new one (one cookie for all subdomains)
// Cookie is a v4 UUID so it can be sent directly to Google Analytics as the client ID
$visitorId = isset($_COOKIE['rh-podcast-redirect']) ? $_COOKIE['rh-podcast-redirect'] : gen_uuid();
setcookie('rh-podcast-redirect', $visitorId, strtotime('+2 years'), '/', 'rockharbor.org');
$clientIp = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
// We want the url with the redirection parameter but without the key since it's unnecessary clutter and may change
$urlParts = explode('?', $_SERVER['REQUEST_URI']);
$pageUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $urlParts[0] . '?url=' . urlencode($url);

// Prepare the Google Analytics POST data
// See here for reference: https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide
$ga = curl_init("https://www.google-analytics.com/collect");
curl_setopt_array($ga, array(
	CURLOPT_POST => TRUE,
	CURLOPT_RETURNTRANSFER => TRUE,
	CURLOPT_POSTFIELDS => http_build_query(array(
		'v' => '1',
		'tid' => $gaProperty,
		'cid' => $visitorId,
		't' => 'pageview',
		'uip' => $clientIp,
		'ua' => $userAgent,
		'dr' => $referer,
		'dl' => $pageUrl
	))
));
$response = curl_exec($ga);
curl_close($ga);

header('Location: ' . $url);
exit();

function gen_uuid() {
	$data = file_get_contents('/dev/urandom', NULL, NULL, 0, 16);
	$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
	$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

	return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
