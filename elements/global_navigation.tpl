<section id="global-navigation" class="clearfix">
	<h1><a href="/"><?php echo $theme->Html->image('header.jpg', array('alt' => 'This is ROCKHARBOR '.$theme->info('short_name'))).$theme->info('short_name'); ?></a></h1>
	<nav>
		<ul>
			<li>
				<a href="#">Campuses</a>
				<?php
				echo $theme->render('campus_menu');
				?>
			</li>
			<li> |<a href="http://www.rockharbor.org/message-archive">Message Archive</a></li>
			<li>
				<?php get_search_form(); ?>
			</li>
		</ul>
	</nav>
</section>