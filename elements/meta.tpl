<?php
global $theme, $post;

if (is_single()) {
	$card = 'summary';
	$tags = '';

	$tags .= '<meta property="og:url" content="'.get_permalink($post->ID).'" />';
	$tags .= '<meta property="og:title" content="'.get_the_title($post->ID).'" />';
	$tags .= '<meta property="og:description" content="'.htmlspecialchars(get_the_excerpt($post->ID)).'" />';

	if (has_post_thumbnail($post->ID)) {
		$attach_id = get_post_thumbnail_id($post->ID);
		$attach = wp_get_attachment_image_src($attach_id, 'thumbnail');
		$image = $attach[0];
		$tags .= '<meta property="og:image" content="'.$image.'" />';
	}

	echo $tags;
}
