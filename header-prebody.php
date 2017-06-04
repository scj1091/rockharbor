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
<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,width=device-width" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<?php
echo $theme->render('meta');
// OpenGraph meta tags
$pageTitle = wp_get_document_title();
$pageUrl = get_permalink();
$pageType = 'website';
$pageImage = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
if (is_front_page()) {
    $postExcerpt = "We are a church of communities following Jesus as we worship, pray, and love others in Orange County, Charlotte, and beyond. Come join us!";
} else if (isset($post->post_excerpt) && $post->post_excerpt != '') {
    $postExcerpt = $post->post_excerpt;
} else {
    $postExcerpt = wp_strip_all_tags($post->post_content, true);
}
 ?>
<!-- OpenGraph meta tags -->
<meta property="og:title" content="<?php echo $pageTitle; ?>" />
<meta property="og:type" content="<?php echo $pageType; ?>" />
<meta property="og:url" content="<?php echo $pageUrl; ?>" />
<meta property="og:image" content="<?php echo $pageImage; ?>" />
<meta name="description" content="<?php echo substr($postExcerpt, 0, 155); ?>" />
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '151499425391027'); // Insert your pixel ID here.
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=151499425391027&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<link rel="icon" href="<?php echo $theme->Html->image('favicon.ico', array( 'parent' => true, 'url' => true )); ?>" type="image/x-icon" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $theme->info('url'); ?>/img/appicon-144.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $theme->info('url'); ?>/img/appicon-114.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $theme->info('url'); ?>/img/appicon-144.png" />
<link rel="apple-touch-icon-precomposed" href="<?php echo $theme->info('url'); ?>/img/appicon-114.png" />
<!-- iOS & Android app install banners -->
<meta name="apple-itunes-app" content="app-id=1230114218" />
<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/android_app_manifest.json" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<!--<link href='//fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>-->
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.min.js" type="text/javascript"></script>
<![endif]-->
<!--[if lte IE 8]>
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/ie8<?php echo WP_DEBUG ? '' : '.min'; ?>.css" />
<![endif]-->
<!--[if lte IE 7]>
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/ie7<?php echo WP_DEBUG ? '' : '.min'; ?>.css" />
<![endif]-->
<script>var RH = RH || {}; RH.base_url = "<?php echo $theme->info('base_url'); ?>";</script>
<?php wp_head(); ?>
<!--[if lte IE 8]>
<script src="<?php echo $theme->info('base_url'); ?>/js/iefixes<?php echo WP_DEBUG ? '' : '.min'; ?>.js"></script>
<![endif]-->
<link type="application/opensearchdescription+xml" rel="search" href="<?php echo $theme->info('base_url') . '/opensearch.php'; ?>" />
<?php
echo $theme->options('zopim_script');
echo $theme->render('analytics');
?>
</head>
