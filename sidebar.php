<?php global $post; ?>
<div id="sub-navigation" class="widget-area">
	<ul>
	<?php 
	wp_list_pages(array(
		'child_of' => $post->ID,
		'title_li' => null
	));
	?>
	</ul>
</div>