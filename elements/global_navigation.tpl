<section id="global-navigation" class="clearfix">
	<h1>
		<a href="/">
			<span class="logo"><?php echo $theme->Html->image('header.jpg', array('alt' => 'This is ROCKHARBOR '.$theme->info('short_name'))); ?></span>
			<span class="title"><?php echo $theme->info('short_name'); ?></span>
		</a>
	</h1>
	<nav>
		<ul>
			<li>
				<a class="dropdown" href="#">Campuses</a>
				<?php
				echo $theme->render('campus_menu');
				?>
			</li>
			<?php wp_nav_menu(array('theme_location' => 'global', 'items_wrap' => '%3$s', 'container' => false, 'menu_class' => false, 'depth' => 1, 'before' => ' | ', 'fallback_cb' => function() { })); ?>
			<li>
				<?php get_search_form(); ?>
			</li>
		</ul>
	</nav>
</section>
