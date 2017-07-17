<style type="text/css">
.ccbpress-group-search {
	background: #eee;
	padding: 6em;
}
.ccbpress-group-search-result {
	background-color: #fff;
	margin-bottom: 2em;
	padding: 1.5em;
	border-right: 2px solid #ddd;
	border-bottom: 2px solid #ddd;
}
.ccbpress-group-search .ccbpress-group-name {
	font-size: 2.5em !important;
	margin-bottom: 10px;
}
.ccbpress-group-search-meta.ccbpress-group-leader {
	padding-left: 0;
}
.ccbpress-group-name span.dashicons-email {
	margin: 0 5px 5px 0;
}
.ccbpress-group-search-meta-container {
	font-size: 1.5em;
	display: inline-block;
	clear: left;
	border: 1px solid #ccc;
	border-radius: 3px;
	padding: 1em;
}
.ccbpress-group-search-meta {
	border-right: 1px solid #ccc;
	margin-right: 0.5em;
}
.ccbpress-group-search-meta:last-child {
	border-right: initial;
}
@media only screen
and (max-device-width: 667px) {
	.ccbpress-group-search {
		padding: 2em;
	}
	.ccbpress-group-search-meta-container {
		border: none;
		padding: 0;
		font-size: 2em;
	}
	.ccbpress-group-search-meta {
		border: none;
		margin: 0 0 1em 0;
		display: inline-block;
		float: left;
		clear: left;
	}
}
</style>
<div class="ccbpress-group-search">
	<?php
	global $theme;
	$override_groupfinder_email = $theme->options('override_groupfinder_email');
	if (isset($override_groupfinder_email) && $override_groupfinder_email != '') {
		$contactHtml = $override_groupfinder_email;
	}
	/**
	 * Loop through each result
	 */
	foreach ( $data['search_results']->response->items->item as $item ) : ?>
		<?php $data['is_valid_args']['item'] = $item; ?>
		<?php if ( $group_profile = $template::is_valid( $data['is_valid_args'] ) ) : ?>
			<div class="ccbpress-group-search-result">

				<div class="ccbpress-group-name clearfix">
					<div class="ccbpress-group-name-large">
						<?php echo $item->name; ?>
					</div>
					<?php if ( TRUE === ( $spots_available = $template::is_full( $group_profile ) ) ) : ?>
						&nbsp;<span class="ccbpress-group-full">(Full Group)</span>
					<?php elseif ( $spots_available != 'unlimited' ) : ?>
						&nbsp;<span class="ccbpress-group-spots-available">(<?php _n( '%s spot available', '%s spots available', $spots_available, 'ccbpress-groups' ) ?></span>
					<?php endif; ?>
					<div class="ccbpress-group-search-meta ccbpress-group-leader">
						<?php if (isset($contactHtml)): ?>
						<span class="dashicons dashicons-admin-links"></span> <?php echo $contactHtml; ?>
						<?php else: ?>
						<span class="dashicons dashicons-email"></span> <a href="<?php echo esc_attr( $template::get_mailto( $group_profile ) ); ?>" target="_blank"<?php echo $esc_html( $data['lightbox_code'] ); ?>><?php echo esc_html( $item->owner_name ); ?></a>
						<?php endif; ?>
					</div>
				</div>

				<div class="ccbpress-group-search-meta-container">
					<?php if ( 0 !== strlen( $item->area_name ) ) : ?>
						<div class="ccbpress-group-search-meta" title="<?php esc_attr_e( 'Area', 'ccbpress-groups' ); ?>">
							<span class="dashicons dashicons-location"></span> <?php echo esc_html( $item->area_name ); ?>
						</div>
					<?php endif; ?>

					<?php if ( 0 !== strlen( $item->group_type_name ) ) : ?>
						<div class="ccbpress-group-search-meta" title="<?php esc_attr_e( 'Group Type', 'ccbpress-groups' ); ?>">
							<span class="dashicons dashicons-tag"></span> <?php echo esc_html( $item->group_type_name ); ?>
						</div>
					<?php endif; ?>

					<?php if ( 0 != strlen( $item->meet_day_name ) ) : ?>
						<div class="ccbpress-group-search-meta" title="<?php esc_attr_e( 'Meeting Day', 'ccbpress-groups' ); ?>">
							<span class="dashicons dashicons-calendar-alt"></span> <?php echo esc_html( $item->meet_day_name ); ?>
						</div>
					<?php endif; ?>

					<?php if ( 0 != strlen( $item->meet_time_name ) ) : ?>
						<div class="ccbpress-group-search-meta" title="<?php esc_attr_e( 'Meeting Time', 'ccbpress-groups' ); ?>">
							<span class="dashicons dashicons-clock"></span> <?php echo esc_html( $item->meet_time_name ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $template::childcare_provided( $group_profile ) ) : ?>
						<div class="ccbpress-group-search-meta" title="<?php esc_attr_e( 'Childcare', 'ccbpress-groups' ); ?>">
							<span class="dashicons dashicons-universal-access"></span> <?php esc_attr_e( 'Childcare Available', 'ccbpress-groups' ); ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="ccbpress-group-description"><?php echo wpautop( $item->description, true ); ?></div>

			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
