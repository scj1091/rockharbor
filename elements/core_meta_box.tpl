<?php

$theme->Html->inputPrefix = 'meta';
$theme->Html->data($data);

echo $theme->Html->input('core_id', array(
	'between' => '<p><strong>CORE ID(s)</strong></p>',
	'after' => '<p><small>A comma-delimited list of CORE ministry IDs to show events from</small></p>',
	'label' => false
));
echo $theme->Html->input('core_event_id', array(
	'between' => '<p><strong>CORE Event ID(s)</strong></p>',
	'after' => '<p><small>A comma-delimited list of individual CORE event IDs to show</small></p>',
	'label' => false
));