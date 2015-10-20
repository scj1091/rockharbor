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
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<?php echo $theme->render('meta'); ?>
<title><?php
	$pageTitle = wp_title('|', false, 'right');

	// Add the blog name.
	$pageTitle .= get_bloginfo('name');

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo('description', 'display');
	if ($site_description && is_front_page()) {
		$pageTitle .= " | $site_description";
	}
	echo $pageTitle;
	?></title>
<?php
// OpenGraph meta tags
$pageUrl = get_permalink();
$pageType = 'website';
$pageImage = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
 ?>
<!-- OpenGraph meta tags -->
<meta property="og:title" content="<?php echo $pageTitle; ?>" />
<meta property="og:type" content="<?php echo $pageType; ?>" />
<meta property="og:url" content="<?php echo $pageUrl; ?>" />
<meta property="og:image" content="<?php echo $pageImage; ?>" />

<link rel="icon" href="<?php echo $theme->Html->image('favicon.ico', array( 'parent' => true, 'url' => true )); ?>" type="image/x-icon" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $theme->info('url'); ?>/img/appicon-144.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $theme->info('url'); ?>/img/appicon-114.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $theme->info('url'); ?>/img/appicon-144.png" />
<link rel="apple-touch-icon-precomposed" href="<?php echo $theme->info('url'); ?>/img/appicon-114.png" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<!--[if lte IE 8]>
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/ie8.css" />
<![endif]-->
<!--[if lte IE 7]>
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/ie7.css" />
<![endif]-->
<script>var RH = RH || {}; RH.base_url = "<?php echo $theme->info('base_url'); ?>";</script>
<?php wp_head(); ?>
<!--[if lte IE 8]>
<script src="<?php echo $theme->info('base_url'); ?>/js/iefixes.js"></script>
<![endif]-->
<link type="application/opensearchdescription+xml" rel="search" href="<?php echo $theme->info('base_url') . '/opensearch.php'; ?>" />
<?php
echo $theme->options('zopim_script');
echo $theme->render('analytics');
?>
</head>
