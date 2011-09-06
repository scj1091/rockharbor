<?php global $theme; ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'rockharbor'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			<?php echo $theme->render('posted_date'); ?>
			<p class="tags">Posted in <?php the_category(', ') . the_tags(' | ', ', '); ?>
			<span class="comments-link"> | <?php comments_popup_link('<span class="leave-reply">' . __('Leave a reply', 'rockharbor') . '</span>', __('<b>1</b> Reply', 'rockharbor'), __('<b>%</b> Replies', 'rockharbor')) ?></span></p>
		</header>

		<div class="entry-content">
			<?php the_content(__('Read More', 'rockharbor')); ?>
			<?php echo $theme->render('pagination_posts'); ?>
		</div>

		<?php
		global $more;
		if ($more) {
			$related = $theme->render('related_content');
			$comments = '';
			if (comments_open()) {
				// have to capture because wordpress just auto-echoes everything
				ob_start();
				?>
				
				<?php comments_template('', true); ?>
				<?php
				$comments = ob_get_clean();
			}
			if (!empty($related) || !empty($comments)) {
				echo $theme->Html->tag('footer', $related.$comments, array('class' => 'related clearfix'));
			}
		}
		?>
	</article>
