<p>
	<label for="<?php echo $widget->get_field_id('title'); ?>">Title</label>
	<input 
		class="widefat" 
		id="<?php echo $widget->get_field_id('title'); ?>" 
		name="<?php echo $widget->get_field_name('title'); ?>"
		value="<?php echo esc_attr($data['title']); ?>"
	/>
</p>
<p>Leave the following fields blank if you wish to fallback to using the settings
	defined in the post.</p>
<p>
	<label for="<?php echo $widget->get_field_id('core_campus_id'); ?>">CORE Campus ID(s)</label>
	<input 
		class="widefat" 
		id="<?php echo $widget->get_field_id('core_campus_id'); ?>" 
		name="<?php echo $widget->get_field_name('core_campus_id'); ?>"
		value="<?php echo esc_attr($data['core_campus_id']); ?>"
	/>
	<small>A comma-delimited list of CORE Campus IDs to show events from</small>
</p>
<p>
	<label for="<?php echo $widget->get_field_id('core_id'); ?>">CORE Ministry ID(s)</label>
	<input 
		class="widefat" 
		id="<?php echo $widget->get_field_id('core_id'); ?>" 
		name="<?php echo $widget->get_field_name('core_id'); ?>"
		value="<?php echo esc_attr($data['core_id']); ?>"
	/>
	<small>A comma-delimited list of CORE Ministry IDs to show events from</small>
</p>
<p>
	<label for="<?php echo $widget->get_field_id('core_involvement_id'); ?>">CORE Involvement ID(s)</label>
	<input 
		class="widefat" 
		id="<?php echo $widget->get_field_id('core_involvement_id'); ?>" 
		name="<?php echo $widget->get_field_name('core_involvement_id'); ?>"
		value="<?php echo esc_attr($data['core_involvement_id']); ?>"
	/>
	<small>A comma-delimited list of CORE Involvement IDs to show events from</small>
</p>