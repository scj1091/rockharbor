<p>If this page should act as an archive template, select the type here.</p>
<?php
$theme->Html->inputPrefix = 'meta';
$theme->Html->data($data);
foreach ($theme->archiveTemplates as $templatename => $label) {
	echo $theme->Html->input('archive_template', array(
		'type' => 'radio',
		'label' => $label,
		'value' => $templatename
	));
}