<form action="<?php echo $theme->info('base_url') . '/action.php'; ?>" method="POST" id="share-your-story">
	<?php
	echo $theme->Html->input('name', array('div' => 'half', 'label' => 'Name'));
	echo $theme->Html->input('email', array('type' => 'email', 'div' => 'half', 'label' => 'Email'));
	echo $theme->Html->input('story', array('type' => 'textarea', 'label' => false));
	echo $theme->Html->input('action', array('type' => 'hidden', 'value' => 'email'));
	echo $theme->Html->input('type', array('type' => 'hidden', 'value' => 'story'));
	echo $theme->Html->captcha();
	echo $theme->Html->input('Submit', array('type' => 'submit', 'label' => false, 'div' => 'submit'));
	?>
</form>
