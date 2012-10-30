<?php
/**
 * Creates the "story box" used across the site
 * 
 * @param integer $id Post id (required)
 * @param string $title Title, if any, to overlay
 * @param string $type Post type
 * @param integer $height Set to force a height
 */

$defaults = array(
	'id' => null,
	'title' => null,
	'type' => 'post',
	'height' => null
);
extract($defaults, EXTR_SKIP);

$out = '';
$options = array(
	'class' => 'story-box',
	'href' => get_permalink($id),
	'style' => null
);
$imageUrl = null;
$attachId = get_post_thumbnail_id($id);
if (!empty($attachId)) {
	$imageUrl = wp_get_attachment_url($attachId);
	$out .= "<img src=\"$imageUrl\" alt=\"$title\" />";
}
if (empty($imageUrl)) {
	$imageUrl = $theme->info('url').'/img/icon-'.$type.'.png';
	$options['style'] .= "min-height:120px; background-image:url($imageUrl);";
}

if ($height) {
	$options['style'] .= "height: {$height}px";
}

if (!empty($title)) {
	$out .= $theme->Html->tag('span', $title, array('class' => 'title'));
}
echo $theme->Html->tag('a', $out, $options);