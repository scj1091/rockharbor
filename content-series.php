<?php 
/**
 * This template part is special and does not use `$wp_query`, but instead uses
 * what's made available in the manual `Message::shortcode()` loop.
 */
global $theme, $post, $item;
$termlink = get_term_link((int)$item->term_id, 'series');
?>
	<article id="post-<?php echo $item->term_id ?>" class="message-series">
		<div class="entry-content">
			<div class="series-image">
				<?php
				if (has_post_thumbnail($item->last->ID)) {
					echo $theme->Html->tag('a', get_the_post_thumbnail($item->last->ID, 'full'), array(
						'href' => $termlink,
						'title' => $item->name
					));
				}
				?>
			</div>
			<div class="series-details">
				<span class="series-title">
					<?php 
					echo $theme->Html->tag('a', $item->name, array(
						'href' => $termlink,
						'title' => $item->name
					));
					?>
				</span>
				<span class="series-date">
					<?php
					echo date('M. j, Y', strtotime($item->series_start_date));
					if ($item->series_start_date != $item->series_end_date) {
						echo ' - '.date('M. j, Y', strtotime($item->series_end_date));
					}
					?>
				</span>
				<span class="series-count">
					<?php 
					$suf = $item->series_message_count > 0 ? 'Messages' : 'Message';
					echo $theme->Html->tag('a', $item->series_message_count." $suf", array(
						'href' => $termlink,
						'title' => $item->name
					));
					?>
				</span>
			</div>
		</div>
	</article>
