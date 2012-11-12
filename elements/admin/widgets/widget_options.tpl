<?php
/**
 * This partial form is appended to each widget that uses the Widget class
 */
?>
<strong>Hide this widget responsively:</strong>
<p>
	<input 
		type="checkbox"
		id="<?php echo $widget->get_field_id('hide_on_mobile'); ?>" 
		name="<?php echo $widget->get_field_name('hide_on_mobile'); ?>"
		value="1"
		<?php if ($data['hide_on_mobile']) { echo 'checked'; } ?>
	/>
	<label for="<?php echo $widget->get_field_id('hide_on_mobile'); ?>">Hide on mobile</label>
	<br />
	<input 
		type="checkbox"
		id="<?php echo $widget->get_field_id('hide_on_tablet'); ?>" 
		name="<?php echo $widget->get_field_name('hide_on_tablet'); ?>"
		value="1"
		<?php if ($data['hide_on_tablet']) { echo 'checked'; } ?>
	/>
	<label for="<?php echo $widget->get_field_id('hide_on_tablet'); ?>">Hide on tablet</label>
	<br />
	<input 
		type="checkbox"
		id="<?php echo $widget->get_field_id('hide_on_desktop'); ?>" 
		name="<?php echo $widget->get_field_name('hide_on_desktop'); ?>"
		value="1"
		<?php if ($data['hide_on_desktop']) { echo 'checked'; } ?>
	/>
	<label for="<?php echo $widget->get_field_id('hide_on_desktop'); ?>">Hide on desktop</label>
</p>