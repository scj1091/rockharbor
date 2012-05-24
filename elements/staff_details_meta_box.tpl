<?php

$theme->Html->inputPrefix = 'meta';
$theme->Html->data($data);

echo $theme->Html->input('first_name', array(
	'before' => '<p>',
	'label' => 'First name',
	'div' => 'half',
	'after' => '</p>'
));
echo $theme->Html->input('last_name', array(
	'before' => '<p>',
	'label' => 'Last name',
	'div' => 'half',
	'after' => '</p>'
));
echo $theme->Html->input('email', array(
	'before' => '<p>',
	'label' => 'Email',
	'div' => 'half',
	'after' => '</p>'
));
echo $theme->Html->input('phone', array(
	'before' => '<p>',
	'label' => 'Phone Number',
	'div' => 'half',
	'after' => '</p>'
));
echo $theme->Html->input('title', array(
	'before' => '<p>',
	'label' => 'Job Title',
	'div' => 'half',
	'after' => '</p>'
));
$theme->Html->inputPrefix = null;
echo $theme->Html->input('tax_input[department]', array(
	'before' => '<p>',
	'label' => 'Ministry',
	'type' => 'select',
	'options' => $departments,
	'div' => 'half',
	'after' => '</p>'
));
$theme->Html->inputPrefix = 'meta';
echo $theme->Html->input('hometown', array(
	'before' => '<p>',
	'label' => 'Hometown',
	'div' => 'half',
	'after' => '</p>'
));
echo $theme->Html->input('family', array(
	'before' => '<p>',
	'label' => 'Family',
	'div' => 'half',
	'after' => '</p>'
));