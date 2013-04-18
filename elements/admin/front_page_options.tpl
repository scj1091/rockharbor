<?php
$theme->Html->inputPrefix = 'meta';
$theme->Html->data($data);
echo $theme->Html->tag('p', 'If you want to force a height for the first featured story, enter a value (in pixels) here.');
echo $theme->Html->tag('p', $theme->Html->tag('strong', 'First Featured Story Height'));
echo $theme->Html->input('first_featured_story_height', array(
	'label' => false,
	'size' => 40
));
echo $theme->Html->tag('p', 'If the page you are featuring contains a video, it will be displayed in the main feature spot. If you want it to simply be the featured image with a link to the page, check this box. If you want the video to show, do not check this box.');
echo $theme->Html->input('first_featured_only_image', array(
	'type' => 'checkbox',
	'label' => 'Prefer Featured Image Over Featured Video',
	'size' => 40
));
