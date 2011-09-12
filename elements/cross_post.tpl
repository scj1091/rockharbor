<?php

global $post;

$theme->Html->inputPrefix = 'meta';
$theme->Html->data($data);

$options = array();
foreach ($blogs as $blog) {
	$options[$blog['blog_id']] = $blog['domain'];
}

echo $theme->Html->input('cross_post', array(
	'type' => 'checkbox',
	'options' => $options
));