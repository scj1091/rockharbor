<?php
if (empty($_REQUEST['action'])) {
	header('Location: '.$_SERVER['HTTP_REFERER']);
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

// if an action is POSTed to the site, the action will be called here
if (method_exists($theme, $_REQUEST['action']) && in_array($_REQUEST['action'], $theme->allowedActions)) {
	$result = call_user_func(array($theme, $_REQUEST['action']));
	if (!empty($theme->messages)) {
		$_SESSION['message']  = implode(', ', $theme->messages);
	}
}

header('Location: '.$_SERVER['HTTP_REFERER']);