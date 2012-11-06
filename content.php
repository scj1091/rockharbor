<?php global $theme; ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
		$video = $theme->render('video');
		if (empty($video) && has_post_thumbnail()) {
			the_post_thumbnail($post->ID, 'full');
		} else {
			$theme->Shortcodes->remove('videoplayer');
			echo $video;
		}
		?>
		<?php if (is_single() || is_search()): ?>
		<header>
			<h1><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'rockharbor'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		</header>
		<?php endif; ?>

		<div class="entry-content">
			<?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'rockharbor')); ?>
			<?php echo $theme->render('pagination_posts'); ?>
		</div>

	</article>
