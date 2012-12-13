<?php
global $theme;
if (!empty($times)) {
	foreach ($times as $time) {
		echo $theme->Html->tag('span', $time, array(
			'class' => 'service-time'
		));
	}
}