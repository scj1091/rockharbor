<?php 
$archive = $theme->isArchive();
get_header(); 
?>
		<section id="content" role="main">
			<header id="content-title">
				<h1 class="page-title">
					<span>
						<?php echo wp_title(''); ?>
					</span>
				</h1>
			</header>
			<nav id="submenu">
				<?php get_sidebar(); ?>
			</nav>
			<?php if (have_posts()): 

				while (have_posts()) {
					the_post();
					$sub = is_search() ? 'search' : get_post_type();
					if ($archive) {
						$sub .= '-more';
					}
					get_template_part('content', $sub); 
				}
				$theme->set('wp_rewrite', $wp_rewrite);
				$theme->set('wp_query', $wp_query);
				echo $theme->render('pagination');
			 else: ?>

			<article id="post-0" class="post no-results not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e('Nothing Found', 'rockharbor'); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'rockharbor'); ?></p>
					<?php get_search_form(); ?>
				</div>
			</article>

			<?php endif; ?>

		</section>
		<?php
		$metadata = $theme->metaToData($post->ID);
		$meta = array_merge(array(
			'core_id' => 0,
			'core_event_id' => 0
		), $metadata);
		// sidebar-core will render core_public_calendar which uses events
		$events = $theme->getCoreMinistryEvents($meta['core_id'], $meta['core_event_id']);
		if (!empty($events)):
		?>
		<section id="sidebar" role="complementary">
			<header id="sidebar-title">
				<h1 class="sub-title"><span>CORE</span></h1>
			</header>
			<?php
			$theme->set('events', $events);
			get_sidebar('core');
			?>
		</section>
		<?php endif;?>

<?php 
get_footer();