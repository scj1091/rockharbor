<?php global $theme; ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
		$meta = $theme->metaToData($post->ID);
		?>
		<div class="entry-content">
			<div class="staff-details clearfix">
				<div class="staff-picture">
					<?php 
					if (!has_post_thumbnail()) {
						echo $theme->Html->image('notpictured.jpg', array('alt' => 'Not pictured', 'parent' => true));
					} else {
						the_post_thumbnail('full');
					}
					?>				
				</div>
				<div class="staff-bio">
					<dl>
					<?php
						echo $theme->Html->tag('dt', 'Email:');
						echo $theme->Html->tag('dd', $meta['email']);
						echo $theme->Html->tag('dt', 'Phone:');
						echo $theme->Html->tag('dd', $meta['phone']);
						$department = get_term($meta['department'], 'department');
						echo $theme->Html->tag('dt', 'Ministry:');
						echo $theme->Html->tag('dd', $department->name);
						echo $theme->Html->tag('dt', 'Job Title:');
						echo $theme->Html->tag('dd', $meta['title']);
						echo $theme->Html->tag('dt', 'Hometown:');
						echo $theme->Html->tag('dd', $meta['hometown']);
						echo $theme->Html->tag('dt', 'Family:');
						echo $theme->Html->tag('dd', $meta['family']);
					?>
					</dl>
				</div>
			</div>
			<?php the_content(__('Read More', 'rockharbor')); ?>
		</div>

	</article>
