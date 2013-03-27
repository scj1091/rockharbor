<form action="<?php echo $theme->info('base_url') . '/action.php'; ?>" method="POST" id="quick-contact" class="clearfix">
	<?php
	wp_nonce_field('action-nonce');
	echo $theme->Html->input('type', array(
		'type' => 'radio',
		'label' => 'Story',
		'id' => 'story',
		'value' => 'story',
		'checked' => true
	));
	echo $theme->Html->input('type', array(
		'type' => 'radio',
		'id' => 'prayer',
		'label' => 'Prayer Request',
		'value' => 'prayer_request'
	));
	echo $theme->Html->input('name', array('label' => 'Name'));
	echo $theme->Html->input('email', array('type' => 'email', 'label' => 'Email'));
	echo $theme->Html->input('request', array('type' => 'textarea', 'label' => false));
	echo $theme->Html->input('action', array('type' => 'hidden', 'value' => 'email'));
	echo $theme->Html->captcha();
	echo $theme->Html->input('Submit', array(
		'type' => 'submit',
		'label' => false,
		'div' => false,
		'style' => 'float:right'
	));
	?>
</form>
