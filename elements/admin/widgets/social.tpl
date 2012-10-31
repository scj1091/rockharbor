<p>
	<label for="<?php echo $widget->get_field_id('title'); ?>">Title</label>
	<input 
		class="widefat" 
		id="<?php echo $widget->get_field_id('title'); ?>" 
		name="<?php echo $widget->get_field_name('title'); ?>"
		value="<?php echo esc_attr($data['title']); ?>"
	/>
</p>
<p>
	<label for="<?php echo $widget->get_field_id('twitter_limit'); ?>">Twitter Count</label>
	<input 
		class="widefat" 
		id="<?php echo $widget->get_field_id('twitter_limit'); ?>" 
		name="<?php echo $widget->get_field_name('twitter_limit'); ?>"
		value="<?php echo esc_attr($data['twitter_limit']); ?>"
	/>
	<small>Number of Tweets to show at a time.</small>
</p>