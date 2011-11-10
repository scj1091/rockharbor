<?php

if (empty($_POST) || empty($_POST['action'])) {
	die('empty post');
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
require_once rtrim(get_template_directory(), DS) . DS . 'libs' . DS. 'rockharbor_theme_base.php';

$theme = new RockharborThemeBase();

// if an action is POSTed to the site, the action will be called here
if (method_exists($theme, $_POST['action']) && in_array($_POST['action'], $theme->allowedActions)) {
	$result = call_user_func(array($theme, $_POST['action']));
	if (!empty($theme->messages)) {
		$_SESSION['message']  = implode(', ', $theme->messages);
	}
}

header('Location: '.rtrim($_SERVER['HTTP_REFERER'], '/'));