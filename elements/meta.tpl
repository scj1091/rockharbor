<?php
global $theme;
$tags = '';

$video = $theme->getEnclosure('video');

$tags .= '<meta property="og:url" content="'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'" />';
$tags .= '<meta property="og:title" content="'.get_the_title().'" />';
$tags .= '<meta property="og:description" content="'.get_the_excerpt().'" />';

if (!empty($video)) {
	$tags .= '<meta property="og:type" content="video" />';
	$tags .= '<meta property="og:video" content="'.$video.'" />';
	$tags .= '<meta property="og:video:type" content="application/x-shockwave-flash" />';
}

if (has_post_thumbnail()) {
	$attach_id = get_post_thumbnail_id();
	$attach = wp_get_attachment_image_src($attach_id, 'large');
	$image = $attach[0];
	$tags .= '<meta property="og:image" content="'.$image.'" />';
}

echo $tags;