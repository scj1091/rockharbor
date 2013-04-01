<?php

$theme->Html->inputPrefix = 'meta';
$theme->Html->data($data);

echo $theme->Html->input('hide_featured_content', array(
	'type' => 'checkbox',
	'label' => 'Hide Featured Content',
	'value' => 1
));
echo $theme->Html->tag('p', 'Hiding everything below makes the content full-width.');
echo $theme->Html->input('hide_submenu', array(
	'type' => 'checkbox',
	'label' => 'Hide submenu',
	'value' => 1
));
echo $theme->Html->input('hide_widgets', array(
	'type' => 'checkbox',
	'label' => 'Hide widgets',
	'value' => 1
));
