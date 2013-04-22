<form action="<?php echo $theme->info('base_url') . '/action.php'; ?>" method="POST" id="<?php echo uniqid('quick-contact'); ?>" class="clearfix">
	<?php
	wp_nonce_field('action-nonce');
	if (!isset($type)) {
		$type = 'all';
	}
	$storyRadio = $theme->Html->input('type', array(
		'type' => 'radio',
		'label' => 'Story',
		'id' => uniqid('story'),
		'value' => 'story',
		'checked' => $type == 'all' || $type == 'story'
	));
	$prayerRequestRadio = $theme->Html->input('type', array(
		'type' => 'radio',
		'id' => uniqid('prayer'),
		'label' => 'Prayer Request',
		'value' => 'prayer_request',
		'checked' => $type == 'prayer_request'
	));
	$feedbackRadio = $theme->Html->input('type', array(
		'type' => 'radio',
		'id' => uniqid('feedback'),
		'label' => 'Website Feedback',
		'value' => 'feedback',
		'checked' => $type == 'feedback'
	));
	switch ($type) {
		case 'story':
			echo $this->Html->tag('div', $storyRadio, array('style' => 'display:none'));
			break;
		case 'prayer_request':
			echo $this->Html->tag('div', $prayerRequestRadio, array('style' => 'display:none'));
			break;
		case 'feedback':
			echo $this->Html->tag('div', $feedbackRadio, array('style' => 'display:none'));
			break;
		default:
			echo $storyRadio.$prayerRequestRadio.$feedbackRadio;
	}
	echo $theme->Html->input('name', array('label' => 'Name', 'id' => uniqid('name')));
	echo $theme->Html->input('email', array('type' => 'email', 'label' => 'Email', 'id' => uniqid('email')));
	echo $theme->Html->input('request', array('type' => 'textarea', 'label' => false, 'required' => true, 'id' => uniqid('request')));
	echo $theme->Html->input('action', array('type' => 'hidden', 'value' => 'email', 'id' => uniqid('action')));
	echo $theme->Html->captcha();
	echo $theme->Html->input('Submit', array(
		'id' => uniqid('submit'),
		'type' => 'submit',
		'label' => false,
		'div' => false,
		'style' => 'float:right'
	));
	?>
</form>
