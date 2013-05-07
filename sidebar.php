<?php
global $post, $theme;

if (!is_search() && !is_404()):
	$children = get_posts(array(
		'numberposts' => -1,
		'post_parent' => $post->ID,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_type' => $post->post_type
	));
	$meta = $theme->metaToData($post->ID);
	if (!empty($children)):
?>
<nav class="submenu widget-area clearfix">
	<?php
	$output = '';
	$maxRow = 5;
	$i = 0;
	$columns = ceil(count($children) / $maxRow);
	$colSize = floor(100 / ($columns)) - 1;
	$class = 'menu clearfix';
	$style = "float:left;width:$colSize%;margin-right: 1%;";
	foreach ($children as $childPost) {
		$link = $theme->Html->tag('a', $childPost->post_title, array(
			'href' => get_permalink($childPost->ID)
		));
		$output .= $theme->Html->tag('li', $link);
		$i++;
		if ($i % $maxRow == 0) {
			echo $theme->Html->tag('ul', $output, compact('style', 'class'));
			$output = '';
		}
	}
	if (!empty($output)) {
		echo $theme->Html->tag('ul', $output, compact('style', 'class'));
	}
	?>
</nav>
	<?php endif; ?>
<?php endif; ?>
