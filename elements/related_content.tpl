<?php

global $post;
$related = false;
$tags = wp_get_post_tags($post->ID);

if (!isset($limit)) {
	$limit = 6;
}
if (!isset($types)) {
	$types = array();
}
$_types = array('post');
$_types = $_types + (array)$types;
if ($tags) {
	$terms = array();
	foreach ($tags as $tag) {
		$terms[] = $tag->term_id;
	}
	$related = get_posts(array(
		'tag__in' => $terms,
		'post__not_in' => array($post->ID),
		'post_type' => $_types,
		'numberposts' => $limit
	));
}
$c = 0;

if ($related): ?>
<ul class="related clearfix">
	<?php
	foreach ($related as $related_post):
		$c++;
		if ($c > 3) {
			$c = 0;
			echo '</ul><ul class="related clearfix">';
		}
	?>
	<li>
		<h2><a href="<?php echo get_permalink($related_post->ID); ?>"><?php echo $related_post->post_title; ?></a></h2>
		<div><?php
		if (has_post_thumbnail($related_post->ID)) {
			$thumb = get_the_post_thumbnail($related_post->ID, 'full');
			echo $theme->Html->tag('a', $thumb, array(
				'href' => get_permalink($related_post->ID)
			));
		} else {
			// do a *simplistic* truncation
			$clean = strip_tags($related_post->post_content);
			$words = explode(' ', $clean);
			$words = array_slice($words, 0, 40);
			$clean = wp_trim_excerpt(implode(' ', $words));
			$clean = strip_shortcodes($clean);
			echo $clean;
		}
		?></div>
		<a href="<?php echo get_permalink($related_post->ID); ?>">Read More</a>
	</li>
	<?php endforeach; ?>
</ul>
<script>
	setTimeout(function() {
		// make the boxes equal height
		$('.related').each(function() {
			var maxHeight = 0;
			$(this)
				.children('li').each(function() {
					if ($(this).height() > maxHeight) {
						maxHeight = $(this).height();
					}
				}).css({
					height: maxHeight
				});
		});
	}, 500);
</script>
<?php endif; ?>