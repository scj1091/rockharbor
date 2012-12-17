<?php
global $theme;
if (!empty($data['images'])) {
	$out = '';
	if (!empty($data['before_content'])) {
		$out .= $theme->Html->tag('p', nl2br($data['before_content']));
	}
	$out .= '<div class="clearfix">';
	$colSize = floor(100 / $data['columns']) - 1;
	foreach ($data['images'] as $key => $image) {
		$out .= "<div style=\"float:left;width:$colSize%;margin-right: 1%;margin-bottom:10px;text-align:center;\">";
		$image = "<img src=\"$image\" />";
		if (!empty($data['image_links'][$key])) {
			$image = $theme->Html->tag('a', $image, array(
				'href' => $data['image_links'][$key],
				'alt' => $data['image_links'][$key]
			));
		}
		$out .= $image;
		$out .= '</div>';
	}
	$out .= '</div>';
	if (!empty($data['after_content'])) {
		$out .= $theme->Html->tag('p', nl2br($data['after_content']));
	}
	echo $out;
}