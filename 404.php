<?php
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

			<article id="post-0" class="post no-results not-found">
				<header class="entry-header clearfix">
					<h1 class="entry-title"><?php _e('Nothing Found', 'rockharbor'); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php _e('Apologies, but this page or file does not exist!', 'rockharbor'); ?></p>
					<?php get_search_form(); ?>
				</div>
			</article>

		</section>

<?php
get_footer();