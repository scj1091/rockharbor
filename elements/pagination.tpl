<nav class="archive-page-link">
<?php
if ($wp_rewrite->using_permalinks()) {
	$base = user_trailingslashit(trailingslashit(remove_query_arg('s', get_pagenum_link(1))).'page/%#%/', 'paged') . "#stories";
} else {
	$base = @add_query_arg('page','%#%') . "#stories";
}
$add_args = false;
if(!empty($wp_query->query_vars['s'])) {
	$add_args = array('s' => get_query_var('s'));
}
echo paginate_links(array(
	'format' => '',
	'total' => $wp_query->max_num_pages,
	'base' => $base,
	'add_args' => $add_args,
	'current' => $wp_query->query_vars['paged'] > 1 ? $wp_query->query_vars['paged'] : 1,
	'prev_text' => __('Prev', 'rockharbor'),
	'next_text' => __('Next', 'rockharbor')
));
?>
</nav>