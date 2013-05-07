<?php

if (is_home() || is_front_page()) {
	return;
}

global $post;
$crumbs = array();
$delim = ' / ';
$link = '<span class="crumb"><a href="%s">%s</a></span>';

$crumbs[] = array('/', 'Home');

if (is_singular() || is_page()) {
	$postParent = $post->post_parent;
	if (is_singular(array_keys($theme->features))) {
		$postParent = $theme->options($post->post_type.'_archive_page_id');
	}
	$childCrumbs = array();
	while ($postParent) {
		$page = get_page($postParent);
		$childCrumbs[] = array(get_permalink($page->ID), get_the_title($page->ID));
		$postParent  = $page->post_parent;
	}
	$crumbs = array_merge($crumbs, array_reverse($childCrumbs));
}

if (is_archive()) {
	$term = get_queried_object();
	if (is_category()) {
		$crumbs[] = 'Category: '.$term->name;;
	} elseif (is_tag()) {
		$crumbs[] = 'Tag: '.$term->name;
	} else {
		// custom tax
		$crumbs[] = ucfirst($term->taxonomy).': '.$term->name;
	}
}

if (!is_archive()) {
	$crumbs[] = get_the_title();
}

foreach ($crumbs as $key => $crumb) {
	if (is_array($crumb)) {
		$crumbs[$key] = sprintf($link, $crumb[0], $crumb[1]);
	} else {
		$crumbs[$key] = "<span class=\"crumb\">$crumb</span>";
	}
}

if (count($crumbs) > 1) {
	echo $theme->Html->tag('div', implode($delim, $crumbs), array(
		'class' => 'breadcrumbs'
	));
}