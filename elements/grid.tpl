<?php
global $theme, $post;
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
		if (has_post_thumbnail()) {
			echo $theme->Html->tag('a', get_the_post_thumbnail($post->ID, 'post-thumbnail'), array(
				'href' => get_permalink(),
				'title' => $post->post_title
			));
		}
		?>

		<div class="grid-title">
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</div>

	</article>
