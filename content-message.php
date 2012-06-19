<?php 
global $theme, $post; 
$meta = $theme->metaToData($post->ID);
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content clearfix">
			<?php if (has_post_thumbnail()): ?>
			<div class="message-image clearfix">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail($post->ID, 'full'); ?>
				</a>
			</div>
			<?php endif; ?>
			<div class="message-details<?php if (has_post_thumbnail()) { echo ' with-image'; } ?>">
				<div class="message-header clearfix">
					<?php 
					echo $theme->Html->tag('a', $post->post_title, array(
						'class' => 'message-title',
						'href' => get_permalink()
					)); 
					$theme->set('date', $post->post_date);
					echo $theme->render('posted_date');
					?>
				</div>
				<div class="message-body">
					<?php the_excerpt(); ?>
				</div>
				<div class="message-meta">
					<span class="meta-title">Tags:</span><span class="meta-value"><?php echo the_tags('', ', '); ?></span>
				</div>
				<div class="message-meta">
					<span class="meta-title">Series:</span><span class="meta-value"><?php echo get_the_term_list($post->ID, 'series', '', ', '); ?></span>
				</div>
				<div class="message-meta">
					<span class="meta-title">Teacher:</span><span class="meta-value"><?php echo get_the_term_list($post->ID, 'teacher', '', ', '); ?></span>
				</div>
				<div class="message-meta">
					<span class="meta-title">Scripture:</span><span class="meta-value"><?php echo $meta['scripture']; ?></span>
				</div>
				<div class="message-meta">
					<span class="meta-title">Length:</span><span class="meta-value"><?php echo $meta['length']; ?></span>
				</div>
			</div>
		</div>
	</article>
