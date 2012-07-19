<?php 
if (isset($message)) {
	echo $theme->Html->tag('div', $theme->Html->tag('p', $message), array('class' => 'updated below-h2'));
}
?>
<h2>Sync Messages</h2>
<form action="upload.php?page=sync_s3" method="post">
	<?php
	if (!empty($objects)) {
		echo $theme->Html->tag('p', 'Choose the items you wish to move to the media library.');
		
		$theme->Html->inputPrefix = 'objects';
		foreach ($objects as $object) {
			echo $theme->Html->input($object, array(
				'type' => 'checkbox',
				'label' => $object
			));
		}
		$theme->Html->inputPrefix = null;
		echo $theme->Html->tag('p', '&nbsp;');
		echo $theme->Html->input('Sync', array(
			'type' => 'submit',
			'label' => false
		));
	} else {
		echo $theme->Html->tag('p', 'There&apos;s nothing that can be moved to the media library!');
		
	}
	?>
</form>