<form action="<?php echo $theme->info('base_url') . '/action.php'; ?>" method="POST" id="share-your-story">
	<?php
	echo $theme->Html->input('name', array('label' => 'Name'));
	echo $theme->Html->input('email', array('type' => 'email', 'label' => 'Email'));
	echo $theme->Html->input('request', array('type' => 'textarea', 'label' => false));
	echo $theme->Html->input('action', array('type' => 'hidden', 'value' => 'email'));
	echo $theme->Html->input('type', array('type' => 'hidden', 'value' => 'prayer_request'));
	echo $theme->Html->captcha();
	echo $theme->Html->input('Submit', array('type' => 'submit', 'label' => false, 'div' => 'submit'));
	?>
</form>
