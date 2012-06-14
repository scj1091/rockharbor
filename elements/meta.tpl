<?php
global $theme;
$tags = '';

$tags .= '<meta property="og:url" content="'.get_permalink().'" />';
$tags .= '<meta property="og:title" content="'.get_the_title().'" />';
$tags .= '<meta property="og:description" content="'.get_the_excerpt().'" />';

if (has_post_thumbnail()) {
	$attach_id = get_post_thumbnail_id();
	$attach = wp_get_attachment_image_src($attach_id, 'large');
	$image = $attach[0];
	$tags .= '<meta property="og:image" content="'.$image.'" />';
}

echo $tags;