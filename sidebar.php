<?php 
global $post, $theme;

?>
<?php if (!empty($post->post_parent)): ?>
<div class="breadcrumb">
	<?php
	$link = get_permalink($post->post_parent);
	$title = get_the_title($post->post_parent);
	echo $theme->Html->tag('a', $title, array('title' => esc_attr($title), 'href' => $link));
	?>
</div>
<?php endif; ?>
<div id="sub-navigation" class="widget-area">
	<ul>
	<?php
	if (empty($post->post_parent)) {
		// no parents, show children of this page
		$page = $post->ID;
	} else {
		// has parents, show this page's siblings
		$page = $post->post_parent;
	}
	
	wp_list_pages(array(
		'child_of' => $page,
		'title_li' => null,
		'depth' => 2
	));
	?>
	</ul>
</div>