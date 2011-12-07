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
				$department = get_term($meta['department'][0], 'department');
				echo $department->name;
				?>
			</a>
		</div>

	</article>
