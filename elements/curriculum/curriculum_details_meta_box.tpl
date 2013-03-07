<?php

$theme->Html->inputPrefix = null;
$theme->Html->data($data);

echo $theme->Html->input('tax_input[curriculum_category]', array(
	'before' => '<p>',
	'label' => 'Category',
	'type' => 'select',
	'options' => $categories,
	'after' => '</p>'
));