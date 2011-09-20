<div id="secondary" class="widget-area">
	<?php
	global $post, $theme;
	$metadata = $theme->metaToData($post->ID);
	if (isset($metadata['core_id']) || isset($metadata['core_event_id'])) {
		$meta = array_merge(array(
			'core_id' => 0,
			'core_event_id' => 0
		), $metadata);
		$theme->set('events', $theme->getCoreMinistryEvents($meta['core_id'], $meta['core_event_id']));
		echo $theme->render('core_public_calendar');
	}
	?>
</div>