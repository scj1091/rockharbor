<div class="ccbpress-group-info">
	<?php 
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( ccbpress_group_info_widget_show_group_name( $group ) ) : ?>
		<div class="ccbpress-group-name"><?php echo $group->name; ?></div>
	<?php endif; ?>
	<?php
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( ccbpress_group_info_widget_show_group_image( $group ) ) : ?>
		<img class="ccbpress-group-image" src="<?php echo $group->image; ?>" alt="<?php echo $group->name; ?>" />
	<?php endif; ?>
	<?php
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( ccbpress_group_info_widget_show_group_desc( $group ) ) : ?>
		<div class="ccbpress-group-description"><?php echo wpautop( $group->description ); ?></div>
	<?php endif; ?>
	<?php
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( ccbpress_group_info_widget_show_group_leader( $group ) ) : ?>
		<div class="ccbpress-group-leader">
			<div class="ccbpress-group-leader-name"><?php echo $group->main_leader->full_name; ?></div>
			<?php
			/**
			 * Check the widget settings to see if it should be displayed.
			 */
			if ( ccbpress_group_info_widget_show_group_leader_email( $group ) || ccbpress_group_info_widget_show_group_leader_phone( $group ) ) : ?>
				<ul>
				<?php
				/**
				 * Check the widget settings to see if it should be displayed.
				 */
				if ( ccbpress_group_info_widget_show_group_leader_email( $group ) ) : ?>
					<li class="ccbpress-group-leader-email"><a href="<?php echo ccbpress_get_easy_email_url( (string)$group->main_leader['id'], (string)$group['id'], $group->main_leader->full_name ); ?>" target="_blank" class="<?php echo ccbpress_lightbox_class(); ?>">Email Leader</a></li>
				<?php endif; ?>
				<?php
				/**
				 * Check the widget settings to see if it should be displayed.
				 */
				if ( ccbpress_group_info_widget_show_group_leader_phone( $group ) ) : ?>
					<?php
					/**
					 * Loop through all of the phone numbers.
					 */
					foreach ( $group->main_leader->phones->phone as $phone ) : ?>
						<?php
						/**
						 * Check that the phone number is not blank.
						 */
						if ( strlen( $phone ) > 0 ) : ?>
							<li class="ccbpress-group-leader-phone"><?php echo $phone; ?> (<?php echo (string)$phone['type']; ?>)</li>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				</ul>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check the widget settings to see if it should be displayed.
	 */
	if ( ccbpress_group_info_widget_show_registration_forms( $group ) ) : ?>
		<div class="ccbpress-group-registration-forms">
			<div class="ccbpress-group-registration-forms-title"><?php _e( 'Registration Form(s):', 'ccbpress' ); ?></div>
			<ul>
				<?php
				/**
				 * Lets start our registration form count at 0
				 */
				$registration_form_count = 0; ?>
				<?php
				/**
				 * Loop through each registration form
				 */
				foreach ( $group->registration_forms->form as $registration_form ) : ?>
					<?php
					/**
					 * Only show it if the form is still active
					 */
					if ( ccbpress_is_form_active( $registration_form ) ) : ?>
						<li><a href="<?php echo $registration_form->url; ?>" class="<?php echo ccbpress_lightbox_class(); ?>"><?php echo $registration_form->name; ?></a></li>
						<?php
						/**
						 * Increase our registration form count by 1
						 */
						$registration_form_count++; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
</div>