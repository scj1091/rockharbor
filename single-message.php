<?php
global $theme, $post;
get_header();
?>
		<section id="content" role="main" class="full">
			<header id="content-title">
				<h1 class="page-title">
					<span>
						<?php echo wp_title(''); ?>
					</span>
				</h1>
			</header>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php
				$meta = $theme->metaToData($post->ID);
				?>
				<div class="entry-content">
					<?php 
					$video = $theme->render('video');
					if (empty($video) && has_post_thumbnail()) {
						the_post_thumbnail($post->ID, 'full');
					} else {
						echo $video;
					}
					?>
					<div class="message-details clearfix">
						<div class="message-header clearfix">
							<?php 
							echo $theme->Html->tag('span', $post->post_title, array('class' => 'message-title')); 
							$theme->set('date', $post->post_date);
							echo $theme->render('posted_date');
							?>
						</div>
						<div class="message-meta">
							<span class="meta-title">Tags:</span><span class="meta-value"><?php echo the_tags('', ', '); ?></span>
						</div>
						<div class="message-meta">
							<span class="meta-title">Series:</span><span class="meta-value"><?php echo get_the_term_list($post->ID, 'series'); ?></span>
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
						<div class="message-body">
							<?php
							echo apply_filters('the_content', $post->post_content);
							?>
						</div>
					</div>
					<div class="message-more share">
						<div class="message-share">
							<h1>Share</h1>
							<?php 
							$theme->set('message', 'Watch "'.get_the_title().'" from @'.$theme->options('twitter_user').' at ');
							echo $theme->render('share'); 
							?>
						</div>
						<div class="message-download">
							<h1>Download</h1>
							<?php
							if (!empty($meta['video_url'])) {
								$icon = $theme->Html->image('video.png', array(
									'alt' => 'Video',
									'parent' => true
								));
								echo $theme->Html->tag('a', $icon, array(
									'href' => $meta['video_url'],
									'title' => 'Download Video',
									'target' => '_blank'
								));
							}
							if (!empty($meta['audio_url'])) {
								$icon = $theme->Html->image('audio.png', array(
									'alt' => 'Audio',
									'parent' => true
								));
								echo $theme->Html->tag('a', $icon, array(
									'href' => $meta['audio_url'],
									'title' => 'Download Audio',
									'target' => '_blank'
								));
							}
							?>
						</div>
						<?php if (!empty($meta['audio_url'])): ?>
						<div class="message-audio">
							<h1>Listen</h1>
							<?php
							echo $theme->render('audio');
							?>
						</div>
						<?php endif; ?>
					</div>
					<footer class="message-related">
						<h1>Related</h1>
						<?php 
						$theme->set('limit', 3);
						$theme->set('types', array('post', 'message'));
						echo $theme->render('related_content'); 
						?>
					</footer>
				</div>
			</article>
		</section>
<?php 
get_footer();