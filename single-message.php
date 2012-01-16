<?php
global $theme;
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
					<?php the_content(__('Read More', 'rockharbor')); ?>
				</div>
			</article>
		</section>
<?php 
get_footer();