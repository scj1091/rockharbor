<?php

$theme->Html->inputPrefix = 'meta';
$theme->Html->data($data);

echo $theme->Html->input('core_id', array(
	'between' => '<p><strong>CORE Ministry ID(s)</strong></p>',
	'after' => '<p><small>A comma-delimited list of CORE ministry IDs to show events from</small></p>',
	'label' => false
));
echo $theme->Html->input('core_involvement_id', array(
	'between' => '<p><strong>CORE Involvement Opportunity ID(s)</strong></p>',
	'after' => '<p><small>A comma-delimited list of individual CORE event IDs to show</small></p>',
	'label' => false
));
echo $theme->Html->input('limit', array(
	'between' => '<p><strong>Event count</strong></p>',
	'after' => '<p><small>Maximum number of events to show (default: unlimited)</small></p>',
	'label' => false
));
echo $theme->Html->input('start_date', array(
	'between' => '<p><strong>Start date</strong></p>',
	'after' => '<p><small>Fetch events on or after this date (default: today)</small></p>',
	'label' => false,
	'type' => 'date'
));
echo $theme->Html->input('end_date', array(
	'between' => '<p><strong>End date</strong></p>',
	'after' => '<p><small>Fetch events up until this date (default: two months from start date)</small></p>',
	'label' => false,
	'type' => 'date'
));
