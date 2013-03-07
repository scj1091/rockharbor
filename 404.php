<?php
get_header();
?>
		<header id="content-title">
			<h1<?php echo wp_title(''); ?></h1>
		</header>
		<section id="content" role="main">

			<article id="post-0" class="post no-results not-found">
				<header>
					<h1><?php _e('Nothing Found', 'rockharbor'); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php _e('Apologies, but this page or file does not exist!', 'rockharbor'); ?></p>
					<?php get_search_form(); ?>
				</div>
			</article>

		</section>

<?php
get_footer();