<form action="<?php echo $theme->info('base_url') . '/action.php'; ?>" method="POST" id="<?php echo uniqid('quick-contact'); ?>" class="clearfix">
	<?php
	wp_nonce_field('action-nonce');
	if (!isset($type)) {
		$type = 'all';
	}
	$storyRadio = $theme->Html->input('type', array(
		'type' => 'radio',
		'label' => 'Story',
		'id' => 'story',
		'value' => 'story',
		'checked' => $type == 'all' || $type == 'story'
	));
	$prayerRequestRadio = $theme->Html->input('type', array(
		'type' => 'radio',
		'id' => 'prayer',
		'label' => 'Prayer Request',
		'value' => 'prayer_request',
		'checked' => $type == 'prayer_request'
	));
	$feedbackRadio = $theme->Html->input('type', array(
		'type' => 'radio',
		'id' => 'feedback',
		'label' => 'Website Feedback',
		'value' => 'feedback',
		'checked' => $type == 'feedback'
	));
	switch ($type) {
		case 'story':
			echo $storyRadio;
			break;
		case 'prayer_request':
			echo $prayerRequestRadio;
			break;
		case 'feedback':
			echo $feedbackRadio;
			break;
		default:
			echo $storyRadio.$prayerRequestRadio.$feedbackRadio;
	}
	echo $theme->Html->input('name', array('label' => 'Name'));
	echo $theme->Html->input('email', array('type' => 'email', 'label' => 'Email'));
	echo $theme->Html->input('request', array('type' => 'textarea', 'label' => false, 'required' => true));
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
