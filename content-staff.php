<?php global $theme; ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<div class="staff-picture">
				<a href="<?php the_permalink(); ?>">
				<?php 
				if (!has_post_thumbnail()) {
					echo $theme->Html->image('notpictured.jpg', array('alt' => 'Not pictured', 'parent' => true));
				} else {
					the_post_thumbnail('full');
				}
				?>
				</a>
			</div>
			<a href="<?php the_permalink(); ?>">
				<?php
				$meta = get_post_custom();
				echo $meta['first_name'][0] . ' ' . $meta['last_name'][0];
				echo '<br />';
				$department = wp_get_post_terms($post->ID, 'department');
				if (!empty($department)) {
					echo $department[0]->name;
				}
				?>
			</a>
		</div>
	</article>
