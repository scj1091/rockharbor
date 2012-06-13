<?php global $theme; ?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="initial-scale=1.0,width=device-width" />
<?php echo $theme->render('meta'); ?>
<title><?php
	wp_title('|', true, 'right');

	// Add the blog name.
	bloginfo('name');

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo('description', 'display');
	if ($site_description && (is_front_page() || is_front_page())) {
		echo " | $site_description";
	}
	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/reset.css" />
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/fonts.css" />
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/lightbox.css" />
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/comments.css" />
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/mediaelementplayer.css" />
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/style.css" />
<link rel="stylesheet" media="all" href="<?php echo $theme->info('url'); ?>/style.css" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<!--[if lte IE 8]>
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/ie8.css" />
<![endif]-->
<!--[if lte IE 7]>
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/ie7.css" />
<![endif]-->
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	wp_enqueue_script('jquery');
	if (is_singular() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
	wp_head();
?>
<?php if (is_front_page()): ?>
<script src="<?php echo $theme->info('base_url'); ?>/js/jquery.accordion.js"></script>
<script>
	jQuery(document).ready(function() {
		var header = 'div.title';
		jQuery('#secondary').accordion({
			autoheight: false,
			header: header,
			alwaysOpen: true,
			event: false
		});
		jQuery('#secondary').find(header+' h3').each(function(index) {
			jQuery(this).click(function() {
				jQuery('#secondary').activate(index);
			});
		});
	});
</script>
<?php endif; ?>
<script src="<?php echo $theme->info('base_url'); ?>/js/mediaelement-and-player.min.js"></script>
<script src="<?php echo $theme->info('base_url'); ?>/js/jquery.lightbox.min.js"></script>
<script>
	jQuery(document).ready(function() {
		// put galleries in lightboxes
		jQuery('.gallery').each(function() {
			var id = jQuery(this).attr('id');
			jQuery(this).find('.gallery-icon a').attr('rel', 'lightbox['+id+']');
		}).lightbox();
		jQuery('.flash-message').delay(5000).slideUp();
		jQuery('img')
			.removeAttr('width')
			.removeAttr('height');
	});
</script>
<!--[if !IE]>-->
<script>
	jQuery(document).ready(function() {
		jQuery('video').mediaelementplayer({
			pluginPath: '<?php echo $theme->info('base_url'); ?>/swf/'
		});
		jQuery('audio').mediaelementplayer({
			pluginPath: '<?php echo $theme->info('base_url'); ?>/swf/',
			audioWidth: 200,
			audioHeight: 30,
			features: ['playpause', 'progress', 'current']
		});
	});
</script>
<!--<![endif]-->
<?php
echo $theme->render('analytics');