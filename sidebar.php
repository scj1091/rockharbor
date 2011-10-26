<?php 
global $post, $theme;

$ancestors = get_post_ancestors($post->ID);
$ancestors = array_reverse($ancestors);
$links = array();
foreach ($ancestors as $ancestor) {
	$ancestorPost = get_post($ancestor);
	$link = $theme->Html->tag('a', $ancestorPost->post_title, array(
		'href' => get_permalink($ancestorPost->ID),
		'title' => esc_attr($ancestorPost->post_title)
	));
	$floor = null;
	if (count($links) > 0) {
		$floor = '&#8970;&nbsp;';
	}
	$links[] = '<div>'.$floor.$link;
}
if (!empty($ancestors)) {
	$links[] = '<div>&#8970;&nbsp;'.$post->post_title;
	$breadbrumb = implode("\n", $links).str_repeat('</div>', count($links));
}

if (!empty($links)) {
	echo $theme->Html->tag('div', $breadbrumb, array('class' => 'breadcrumb'));
}

?>
<div id="sub-navigation" class="widget-area">
	<ul>
	<?php
	if (empty($ancestors)) {
		// no parents, show children of this page
		$page = $post->ID;
	} else {
		// has parents, show this page's siblings
		$page = $post->post_parent;
	}
	wp_list_pages(array(
		'child_of' => $page,
		'title_li' => null
	));
	?>
	</ul>
</div>