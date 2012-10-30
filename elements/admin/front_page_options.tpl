<?php
$theme->Html->inputPrefix = 'meta';
$theme->Html->data($data);
echo $theme->Html->tag('p', 'If you want to force a height for the first featured story, enter a value (in pixels) here.');
echo $theme->Html->tag('p', $theme->Html->tag('strong', 'First Featured Story Height'));
echo $theme->Html->input('first_featured_story_height', array(
	'label' => false,
	'size' => 40
));
