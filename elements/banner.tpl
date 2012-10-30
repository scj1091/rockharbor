<?php 

global $post;

if (is_front_page()) {
	$meta = $theme->metaToData($post->ID);
	$locations = get_nav_menu_locations();
	if (isset($locations['featured'])) {
		$items = wp_get_nav_menu_items('featured');
		// only 4 items allowed
		$items = array_slice($items, 0, 4);
		foreach ($items as $key => $item) {
			$theme->set('height', null);
			if ($key === 0 && !empty($meta['first_featured_story_height'])) {
				$theme->set('height', $meta['first_featured_story_height']);
			}
			$theme->set('id', $item->object_id);
			$theme->set('title', $item->title);
			$theme->set('type', $item->object);
			echo $theme->render('story_box');
		}
	}
} else {
	echo $this->Html->image('banner.jpg', array('parent' => true, 'alt' => 'ROCKHARBOR is a church of communities living out the gospel together.', 'class' => 'full'));
}