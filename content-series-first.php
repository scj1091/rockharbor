<?php 
/**
 * This template part is special and does not use `$wp_query`, but instead uses
 * what's made available in the manual `Message::shortcode()` loop.
 * 
 * This is especially special because it's used only to display the first 
 * series
 */
global $theme, $post, $item;
$permalink = get_permalink($item->last->ID);
?>
	<article id="post-<?php echo $item->term_id ?>" class="first message-series clearfix">
		<div class="entry-content">
			<div class="series-image">
				<?php
				if (has_post_thumbnail($item->last->ID)) {
					echo $theme->Html->tag('a', get_the_post_thumbnail($item->last->ID, 'full'), array(
						'href' => $permalink,
						'title' => $item->last->post_title
					));
				}
				?>
			</div>
			<div class="series-details">
				<span class="series-title">
					<?php 
					echo $theme->Html->tag('a', $item->last->post_title, array(
						'href' => $permalink,
						'title' => $item->last->post_title
					));
					?>
				</span>
				<span class="series-date">
					<?php
					$date = date('Y-m-d', strtotime($item->last->post_date));
					$theme->set('date', $date);
					echo $theme->render('posted_date');
					?>
				</span>
				<span class="message-excerpt">
					<?php echo $item->last->post_excerpt; ?>
				</span>
			</div>
		</div>
	</article>

	<h1>Past Messages</h1>
