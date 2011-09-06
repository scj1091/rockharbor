<?php global $theme; ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'rockharbor'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			<?php echo $theme->render('posted_date'); ?>
			<p class="tags">Posted in <?php the_category(', ') . the_tags(' | ', ', '); ?></p>
		</header>

		<div class="entry-content">
			<?php the_content(__('Read More', 'rockharbor')); ?>
			<?php echo $theme->render('pagination_posts'); ?>
		</div>

		<?php
		global $more;
		if ($more) {
			$related = $theme->render('related_content');
			if (!empty($related)) {
				echo $theme->Html->tag('footer', $related, array('class' => 'related clearfix'));
			}
		}
		?>
	</article>
