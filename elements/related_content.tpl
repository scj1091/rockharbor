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
$_types = array_merge($_types, (array)$types);
if ($tags) {
	$terms = array();
	foreach ($tags as $tag) {
		$terms[] = $tag->term_id;
	}
	$related = get_posts(array(
		'tag__in' => $terms,
		'post__not_in' => array($post->ID),
		'post_type' => $_types,
		'posts_per_page' => $limit
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
		<div><?php echo $related_post->post_content; ?></div>
		<a href="<?php echo get_permalink($related_post->ID); ?>">Read More</a>
	</li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>