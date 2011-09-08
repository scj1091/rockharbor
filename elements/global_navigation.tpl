<section id="global-navigation" class="clearfix">
	<h1><a href="/"><?php echo $theme->Html->image('banner.png', array('alt' => 'This is ROCKHARBOR '.$theme->info('name'))); ?></a></h1>
	<nav>
		<ul>
			<li>
				<a href="#">Campuses</a>
				<ul>
					<li><a href="http://fullerton.rockharbor.org">Fullerton</a></li>
					<li><a href="http://southcounty.rockharbor.org">South County</a></li>
				</ul>
			</li>
			<li> |<a href="http://www.rockharbor.org/message-archive">Message Archive</a></li>
			<li>
				<?php get_search_form(); ?>
			</li>
		</ul>
	</nav>
</section>