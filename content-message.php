<?php global $theme; ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</div>
	</article>
