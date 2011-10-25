<?php
if (empty($link)) {
	return;
}
$link = $this->Html->tag('a', $title, array(
	'href' => $link,
	'target' => '_blank'
));
echo $this->Html->tag('span', $link, array(
	'class' => 'icon-map'
));