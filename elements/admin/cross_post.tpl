<?php

global $post;

$theme->Html->inputPrefix = 'meta';

$theme->Html->data($data);

$options = array();
foreach ($blogs as $blog) {
	echo $theme->Html->input('cross_post_'.$blog['blog_id'], array(
		'type' => 'checkbox',
		'label' => $blog['domain'],
		'value' => 1
	));
}

if (empty($blogs)) {
	echo $theme->Html->tag('p', 'This site is not allowed to cross post to other sites. Please have a network admin enable this feature.');
}
