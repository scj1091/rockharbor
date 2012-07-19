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
