<div>
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
		<label for="<?php echo $widget->get_field_id('username'); ?>">Instagram Username</label>
		<input
			class="widefat"
			id="<?php echo $widget->get_field_id('username'); ?>"
			name="<?php echo $widget->get_field_name('username'); ?>"
			value="<?php echo esc_attr($data['username']); ?>"
		/>
	</p>
	<p>
		<label for="<?php echo $widget->get_field_id('columns'); ?>">Columns</label>
		<input
			class="widefat"
			id="<?php echo $widget->get_field_id('columns'); ?>"
			name="<?php echo $widget->get_field_name('columns'); ?>"
			size="5"
			value="<?php echo esc_attr($data['columns']); ?>"
		/>
		<small>Number of columns to divide the images into.</small>
	</p>
	<p>
		<label for="<?php echo $widget->get_field_id('limit'); ?>">Image Count</label>
		<input
			class="widefat"
			id="<?php echo $widget->get_field_id('limit'); ?>"
			name="<?php echo $widget->get_field_name('limit'); ?>"
			size="5"
			value="<?php echo esc_attr($data['limit']); ?>"
		/>
		<small>Number of images to show.</small>
	</p>
	<p>
		<label for="<?php echo $widget->get_field_id('before_content'); ?>">Before Content</label>
		<textarea
			class="widefat"
			id="<?php echo $widget->get_field_id('before_content'); ?>"
			name="<?php echo $widget->get_field_name('before_content'); ?>"
		><?php echo esc_attr($data['before_content']); ?></textarea>
		<small>This content is placed before the image grid.</small>
	</p>
	<p>
		<label for="<?php echo $widget->get_field_id('after_content'); ?>">After Content</label>
		<textarea
			class="widefat"
			id="<?php echo $widget->get_field_id('after_content'); ?>"
			name="<?php echo $widget->get_field_name('after_content'); ?>"
		><?php echo esc_attr($data['after_content']); ?></textarea>
		<small>This content is placed after the image grid.</small>
	</p>
</div>