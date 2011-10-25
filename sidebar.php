<?php global $post; ?>
<div id="sub-navigation" class="widget-area">
	<ul>
	<?php
	$ancestors = get_post_ancestors($post->ID);
	if (empty($ancestors)) {
		$page = $post->ID;
	} else {
		$root = count($ancestors)-1;
		$page = $ancestors[$root];
	}
	wp_list_pages(array(
		'child_of' => $page,
		'title_li' => null
	));
	?>
	</ul>
</div>