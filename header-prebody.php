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
<meta name="viewport" content="width=device-width" />
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
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/style.css" />
<link rel="stylesheet<?php if (WP_DEBUG) { echo '/less'; } ?>" media="all" href="<?php echo $theme->info($theme->isChildTheme() ? 'theme_url' : 'base_url'); ?>/css/colors.<?php echo WP_DEBUG ? 'less' : 'css'; ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php
	wp_enqueue_script('jquery');
	if (is_singular() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
	wp_head();
?>
<?php if (is_front_page()): ?>
<script src="<?php echo $theme->info('base_url'); ?>/js/jquery.dimensions.min.js"></script>
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
<script src="<?php echo $theme->info('base_url'); ?>/js/swfobject.js"></script>
<script src="<?php echo $theme->info('base_url'); ?>/js/jquery.lightbox.min.js"></script>
<script>
	jQuery(document).ready(function() {
		// put galleries in lightboxes
		jQuery('.gallery').each(function() {
			var id = jQuery(this).attr('id');
			jQuery(this).find('.gallery-icon a').attr('rel', 'lightbox['+id+']');
		}).lightbox();
	});
</script>
<?php 
/**
 * The following script should _only_ be included if in debug mode, because having
 * the client compile .less files is a unnecessary preformance hit. Please
 * compile the .less to .css before posting to production
 */
if (WP_DEBUG): 	
?>
<script src="<?php echo $theme->info('base_url'); ?>/js/less-1.1.3.min.js"></script>
<?php endif; ?>
</head>