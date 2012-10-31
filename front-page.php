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
		<section id="content" role="main">
			<header id="content-title">
				<h1 class="page-title">
					<span>
						<?php 
						echo $post->post_title; 
						?>
					</span>
				</h1>
			</header>
			<?php 
				// make WordPress treat these as partial posts
				$more = 0;
				$theme->aggregatePosts();
			
				while (have_posts()) {
					the_post();
					switch_to_blog($post->blog_id);
					get_template_part('content', get_post_type()); 
				}
				switch_to_blog($theme->info('id'));
				$theme->set('wp_rewrite', $wp_rewrite);
				$theme->set('wp_query', $wp_query);
				echo $theme->render('pagination');
				?>
		</section>
		
		<section id="sidebar" role="complementary">
			<?php
			dynamic_sidebar('sidebar-frontpage');
			?>
		</section>

<?php 
get_footer();