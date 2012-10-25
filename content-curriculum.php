<?php 
global $theme, $post; 
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content clearfix">
			<div class="curriculum-details">
				<?php
				echo $theme->Html->tag('a', $post->post_title, array(
					'href' => get_permalink(),
					'title' => 'Download '.$post->post_title
				));
				?>
			</div>
		</div>
	</article>
