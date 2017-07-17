<?php
global $theme;
$meta = $theme->metaToData($post->ID);
if (!empty($meta['ccbpress_filter_value'])) {
	$attributeMap = array(
		'group' => 'group_id',
		'group_type' => 'group_type',
		'department' => 'department',
		'campus' => 'campus_id'
	);
	$upcomingEventsShortcode = '[ccbpress_upcoming_events'
		. ( !empty($meta['ccbpress_start_date']) ? " date_start=\"{$meta['ccbpress_start_date']}\"" : '' )
		. ( " date_range=\"{$meta['ccbpress_search_length']}\"" )
		. ( !empty($meta['ccbpress_how_many']) ? " how_many=\"{$meta['ccbpress_how_many']}\"" : '' )
		. ( " theme=\"{$meta['ccbpress_theme']}\"" )
		. ( " filter_by=\"{$meta['ccbpress_filter_by']}\"" )
		. ( " {$attributeMap[$meta['ccbpress_filter_by']]}=\"{$meta['ccbpress_filter_value']}\"" )
		. ( !empty($meta['ccbpress_exclude']) ? " exclude=\"{$meta['ccbpress_exclude']}\"" : '' )
		. ( ']' );
	$upcomingEventsOut = '<div class="rh-upcoming-events"><h3>Upcoming Events</h3>' . do_shortcode($upcomingEventsShortcode) . '</div>';
}
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if (is_single() || is_search()): ?>
		<header>
			<h2><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'rockharbor'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		</header>
		<?php endif; ?>

		<div class="entry-content <?php if (!empty($upcomingEventsOut)) { echo "narrow-entry-content "; }?>clearfix">
			<?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'rockharbor')); ?>
			<?php echo $theme->render('pagination_posts'); ?>
		</div>
		<?php if (isset($upcomingEventsOut)) { echo $upcomingEventsOut; } ?>

	</article>
