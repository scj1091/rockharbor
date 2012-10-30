<?php
echo $theme->Html->tag('p', 'Type the url, if any, you would like the Featured Image to link to.');
$theme->Html->inputPrefix = 'meta';
$theme->Html->data($data);
echo $theme->Html->tag('p', $theme->Html->tag('strong', 'URL'));
echo $theme->Html->input('featured_image_link', array(
	'label' => false,
	'size' => 100
));
