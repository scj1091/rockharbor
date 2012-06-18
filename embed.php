<?php

/**
 * This file is used 
 * 
 * 
 *  
 */


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

?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/mediaelementplayer.css" />
		<?php if ($theme->isChildTheme()): ?>
		<link rel="stylesheet" media="all" href="<?php echo $theme->info('url'); ?>/style.css" />
		<?php endif; ?>
		<?php
			wp_enqueue_script('jquery');
			wp_head();
		?>
		<script src="<?php echo $theme->info('base_url'); ?>/js/mediaelement-and-player.min.js"></script>
		<script>
			jQuery(document).ready(function() {
				jQuery('video').mediaelementplayer({
					pluginPath: '<?php echo $theme->info('base_url'); ?>/swf/'
				});
			});
		</script>
		<?php echo $theme->render('analytics'); ?>
		<style>
			html, body {
				margin: 0;
				padding: 0;
			}
		</style>
	</head>
	<body>
		<?php
		if (isset($_GET['post']) && isset($_GET['blog'])) {
			switch_to_blog($_GET['blog']);
			query_posts(array(
				'p' => $_GET['post'],
				'post_type' => 'message'
			));
			if (have_posts()) {
				the_post();
				echo $theme->render('video');
			}
		}
		?>
	</body>
</html>