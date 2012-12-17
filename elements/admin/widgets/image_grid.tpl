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
		<label for="<?php echo $widget->get_field_id('before_content'); ?>">Before Content</label>
		<textarea 
			class="widefat" 
			id="<?php echo $widget->get_field_id('before_content'); ?>" 
			name="<?php echo $widget->get_field_name('before_content'); ?>"
		><?php echo esc_attr($data['before_content']); ?></textarea>
		<small>This content is placed before the image grid.</small>
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
	<hr />
	<p><strong>Images</strong></p>
	<div id="<?php echo $widget->get_field_id('images'); ?>">
		<?php
		if (empty($data['images'])) {
		?>
			<p>
				<a title="Add Image" class="mceButton mceButtonEnabled add_image" href="javascript:;" role="button" tabindex="-1" style="text-decoration: none;">
					<img alt="Add Image" src="<?php echo $theme->info('base_url'); ?>/js/mceplugins/video.png" class="mceIcon" />
				</a>
				<input 
					class="widefat img-src" 
					id="<?php echo $widget->get_field_id("images]["); ?>" 
					name="<?php echo $widget->get_field_name("images]["); ?>"
					style="width: 70%"
					value=""
				/>
				<br />
				<label for="<?php echo $widget->get_field_id("image_links]["); ?>">Link:</label>
				<input 
					class="widefat" 
					id="<?php echo $widget->get_field_id("image_links]["); ?>" 
					name="<?php echo $widget->get_field_name("image_links]["); ?>"
					style="width: 70%"
					value=""
				/>
				<a style="display:none" href="javascript:;" onclick="RH.removeImageGridImage(this)">X</a>
			</p>
		<?php
		} else {
			foreach ($data['images'] as $key => $image) {
			?>
			<p>
				<a title="Add Image" class="mceButton mceButtonEnabled add_image" href="javascript:;" role="button" tabindex="-1" style="text-decoration: none;">
					<img alt="Add Image" src="<?php echo $theme->info('base_url'); ?>/js/mceplugins/video.png" class="mceIcon" />
				</a>
				<input 
					class="widefat img-src" 
					id="<?php echo $widget->get_field_id("images]["); ?>" 
					name="<?php echo $widget->get_field_name("images]["); ?>"
					style="width: 70%"
					value="<?php echo esc_attr($image); ?>"
				/>
				<br />
				<label for="<?php echo $widget->get_field_id("image_links]["); ?>">Link:</label>
				<input 
					class="widefat" 
					id="<?php echo $widget->get_field_id("image_links]["); ?>" 
					name="<?php echo $widget->get_field_name("image_links]["); ?>"
					style="width: 70%"
					value="<?php echo esc_attr($data['image_links'][$key]); ?>"
				/>
				<a href="javascript:;" onclick="RH.removeImageGridImage(this)">X</a>
			</p>
		<?php
			}
		}
		?>
	</div>
	<hr />
	<a href="javascript:RH.addImageGridImage('<?php echo $widget->get_field_id('images'); ?>')">+ Add an image</a>
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
<script>
jQuery('.add_image').live('click', function() {
	var el = this;
	RH.showMediaLibrary(function(html) {
		var url = jQuery(html).attr('href');
		jQuery(el).siblings('input').val(url);
		return '';
	});
});
</script>