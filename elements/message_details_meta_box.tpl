<?php

$theme->Html->inputPrefix = 'meta';
$theme->Html->data($data);

echo $theme->Html->input('series', array(
	'before' => '<p>',
	'label' => 'Message Series',
	'type' => 'select',
	'options' => $series,
	'after' => '</p>'
));
echo $theme->Html->input('scripture', array(
	'before' => '<p>',
	'label' => 'Scripture',
	'after' => '</p>'
));
echo $theme->Html->input('length', array(
	'before' => '<p>',
	'label' => 'Length',
	'after' => '</p>'
));
