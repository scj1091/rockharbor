<?php
/* In order to use this template as the front page, make sure to set it in
 * WordPress' backend
 *
 * Settings > Reading > Front page displays
 *
 * Choose "A static page" and select your home page under the "Front page"
 * dropdown
 */
global $wp_rewrite, $wp_query, $more, $wpdb, $post;
get_header();
?>
		<section id="frontpage-sidebar" role="complementary">
			<?php
			dynamic_sidebar('sidebar-frontpage');
			?>
		</section>

		<section id="frontpage-content" role="main">
			<header id="frontpage-title">
				<h1><?php echo $post->post_title; ?></h1>
			</header>
			<article class="stories-2">
			<?php
				// make WordPress treat these as partial posts
				$more = 0;
				$theme->aggregatePosts();

				while (have_posts()) {
					the_post();
					switch_to_blog($post->blog_id);
					$theme->set('id', $post->ID);
					$theme->set('title', $post->post_title);
					$theme->set('type', $post->post_type);
					if ($post->blog_id !== $theme->info('id')) {
						$theme->set('blog', $post->blog_id);
					} else {
						$theme->set('blog', null);
					}
					echo $theme->render('story_box');
				}
				switch_to_blog($theme->info('id'));
				?>
			</article>
			<?php
			$theme->set('wp_rewrite', $wp_rewrite);
			$theme->set('wp_query', $wp_query);
			echo $theme->render('pagination');
			?>
		</section>

<?php
get_footer();