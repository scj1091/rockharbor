<?php

$theme->Html->inputPrefix = 'meta';
$theme->Html->data($data);

echo $theme->Html->input('hide_submenu', array(
	'type' => 'checkbox',
	'label' => 'Hide submenu',
	'value' => 1
));