<style type="text/css">
.group-search-line-break {
	clear: left;
}
.group-search-checkbox > input {
	width: auto;
}
/* Toggles */
.toggle-button-window {
	width: 108px;
	display: inline-block;
	cursor: pointer;
	border-radius: 4px;
	border: 1px solid #ccc;
	position: relative;
	overflow: hidden;
}
.toggle-button-background {
	width: 159px;
	margin-left: -53px;
	transition: margin-left 0.5s
}
.toggle-button-pane {
	width: 53px;
	padding: 6px 12px;
	display: inline-block;
	font-size: 1.4em;
	line-height: 1.1em;
	vertical-align: top;
	text-align: center;
}
.toggle-button-pane.on {
	background-color: #428bca;
	color: #fff;
}
.toggle-button-pane.handle {
	background-color: #fff;
}
.toggle-button-pane.off {
	background-color: #eee;
	color: #000;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery.fn.extend({
		rhToggleButton: function() {
			return this.each(function() {
				thisObj = jQuery(this);
				thisObj.css("opacity", 0);
				var div = jQuery('<div class="toggle-button-window" data-input-element="' + thisObj.prop('id') + '"><div class="toggle-button-background"><span class="toggle-button-pane on">ON</span><span class="toggle-button-pane handle">&nbsp;</span><span class="toggle-button-pane off">OFF</span></div></div>').insertBefore(this);
				div.on("click", function() {
					var thisObj = jQuery(this);
					var input = jQuery("#" + thisObj.data("input-element"));
					var state = input.prop("checked");
					var newMargin = state ? "-53px" : "0px";
					jQuery(input).prop("checked", !state);
					thisObj.children().css("margin-left", newMargin);
				});
				if (thisObj.prop("checked")) {
					div.children().css("margin-left", 0);
				}
			});
		}
	});
});
jQuery(document).ready(function() {
	jQuery("#ccbpress_childcare_available, #ccbpress_exclude_full").rhToggleButton();
});
</script>
<form method="post" action="<?php echo esc_url( $data['page_permalink'] ); ?>">
	<?php
	/**
	 * Check to see if the campus list should be displayed.
	 */
	if ( $template::show_campus( $data['campus_list_dropdown'] ) ) : ?>
		<div class="ccbpress-group-search-filter">
			<?php echo $data['campus_list_dropdown']; ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check to see if the area list should be displayed.
	 */
	if ( $template::show_area( $data['area_list_dropdown'] ) ) : ?>
		<div class="ccbpress-group-search-filter">
			<?php echo $data['area_list_dropdown']; ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check to see if the meet day list should be displayed.
	 */
	if ( $template::show_meet_day( $data['meet_day_list_dropdown'] ) ) : ?>
		<div class="ccbpress-group-search-filter">
			<?php echo $data['meet_day_list_dropdown']; ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check to see if the meet time list should be displayed.
	 */
	if ( $template::show_meet_time( $data['meet_time_list_dropdown'] ) ) : ?>
		<div class="ccbpress-group-search-filter">
			<?php echo $data['meet_time_list_dropdown']; ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check to see if the department list should be displayed.
	 */
	if ( $template::show_department( $data['department_list_dropdown'] ) ) : ?>
		<div class="ccbpress-group-search-filter">
			<?php echo $data['department_list_dropdown']; ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check to see if the group type list should be displayed.
	 */
	if ( $template::show_group_type( $data['group_type_list_dropdown'] ) ) : ?>
		<div class="ccbpress-group-search-filter">
			<?php echo $data['group_type_list_dropdown']; ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check to see if the udf_grp_pulldown_1 list should be displayed.
	 */
	if ( $template::show_udf_grp_pulldown_1( $data['udf_grp_pulldown_1_list_dropdown'] ) ) : ?>
		<div class="ccbpress-group-search-filter">
			<?php echo $data['udf_grp_pulldown_1_list_dropdown']; ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check to see if the udf_grp_pulldown_2 list should be displayed.
	 */
	if ( $template::show_udf_grp_pulldown_2( $data['udf_grp_pulldown_2_list_dropdown'] ) ) : ?>
		<div class="ccbpress-group-search-filter">
			<?php echo $data['udf_grp_pulldown_2_list_dropdown']; ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check to see if the udf_grp_pulldown_3 list should be displayed.
	 */
	if ( $template::show_udf_grp_pulldown_3( $data['udf_grp_pulldown_3_list_dropdown'] ) ) : ?>
		<div class="ccbpress-group-search-filter">
			<?php echo $data['udf_grp_pulldown_3_list_dropdown']; ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check to see if the childcare checkbox should be displayed.
	 */
	if ( $template::show_childcare() ) : ?>
		<div class="ccbpress-group-search-filter group-search-line-break group-search-checkbox">
			<label><?php _e( 'Only Groups with Childcare', 'ccbpress' ); ?></label>
			<input type="checkbox" id="ccbpress_childcare_available" name="childcare" value="1" <?php $template::childcare_checked(); ?>/>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Check to see if the exclude full groups checkbox should be displayed.
	 */
	if ( $template::show_exclude_full_groups() ) : ?>
		<div class="ccbpress-group-search-filter group-search-checkbox">
			<label><?php _e( 'Exclude Full Groups', 'ccbpress' ); ?></label>
			<input type="checkbox" id="ccbpress_exclude_full" name="exclude_full" value="1" <?php $template::exclude_full_checked(); ?>/>
		</div>
	<?php endif; ?>
	<div class="ccbpress-group-search-button">
		<button type="submit" name="ccbpress_submit" title="Refresh"><?php _e( 'Refresh', 'ccbpress' ); ?></button>
	</div>
</form>
