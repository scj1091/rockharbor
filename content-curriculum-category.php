<?php 
/**
 * This template part is special and does not use `$wp_query`, but instead uses
 * what's made available in the manual `Message::shortcode()` loop.
 */
global $theme, $item;


$link = $theme->Html->tag('a', $item->name, array(
	'href' => get_term_link($item->slug, $item->taxonomy)
));
?>
<div class="curriculum-category">
	<?php echo $link; ?>
</div>