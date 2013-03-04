<?php
if (isset($message)) {
	echo $theme->Html->tag('div', $theme->Html->tag('p', $message), array('class' => 'updated below-h2'));
}
?>
<h2>Move Messages</h2>
<?php
if (!empty($objects)) {
	echo $theme->Html->tag('p', 'Choose the item you wish to move to the media library.');
	foreach ($objects as $object) {
		$form = null;
		$form .= $theme->Html->input('name', array(
			'type' => 'hidden',
			'value' => $object
		));
		$form .= $theme->Html->input($object, array(
			'type' => 'submit',
			'label' => false
		));
		echo $theme->Html->tag('form', $form, array(
			'action' => 'upload.php?page=sync_s3',
			'method' => 'post'
		));
	}
} else {
	echo $theme->Html->tag('p', 'There&apos;s nothing that can be moved to the media library!');

}