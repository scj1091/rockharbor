<?php
global $post;
$meta = $theme->metaToData($post->ID);

if (!empty($meta['document_url'])) {
	$url = parse_url($meta['document_url']);
	$filename = array_pop(explode('/', $url['path']));

	// force download
	header('Content-disposition: attachment; filename="'.$filename.'"');

	echo file_get_contents($meta['document_url']);
} else {
	get_template_part('404');
}