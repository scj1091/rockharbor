<?php
global $theme;
if (!empty($data['results'])) {
	$out = '';
	if (!empty($data['before_content'])) {
		$out .= $theme->Html->tag('p', nl2br($data['before_content']));
	}
	$out .= '<div class="clearfix">';
	$colSize = floor(100 / $data['columns']) - 1;
	foreach ($data['results'] as $key => $image) {
		$out .= "<div style=\"float:left;width:$colSize%;margin-right: 1%;margin-bottom:10px;text-align:center;\">";
		$out .= '<a target="_blank" href="http://instagram.com/'.$data['username'].'">';
		$out .= "<img src=\"$image\" />";
		$out .= '</a>';
		$out .= '</div>';
	}
	$out .= '</div>';
	if (!empty($data['after_content'])) {
		$out .= $theme->Html->tag('p', nl2br($data['after_content']));
	}
	echo $out;
}