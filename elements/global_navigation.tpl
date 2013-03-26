<section class="global-navigation clearfix">
	<h1 class="clearfix">
		<a href="/">
			<?php
			echo '<div class="logo">';
			echo $theme->Html->image('logo.png', array(
				'alt' => 'RH',
				'parent' => false
			));
			echo $this->Html->tag('span', $theme->info('short_name'), array(
				'class' => 'secondary desktop-hide tablet-hide'
			));
			echo $theme->Html->image('textlogo-white.png', array(
				'alt' => 'ROCKHARBOR',
				'class' => 'mobile-hide',
				'parent' => true
			));
			echo '</div>';
			echo '<div class="title">';
			if (!$theme->info('hide_name_in_global_nav')) {
				echo $this->Html->tag('span', $theme->info('name'), array(
					'class' => 'mobile-hide'
				));
			}
			echo '</div>';
			?>
		</a>
	</h1>
	<nav>
		<ul class="clearfix">
			<li class="menu">
				<!-- for mobile use only -->
			</li>
			<li class="campuses">
				<a class="dropdown" href="#">Campuses</a>
				<?php
				echo $theme->render('campus_menu');
				?>
			</li>
			<li class="search">
				<?php get_search_form(); ?>
			</li>
		</ul>
	</nav>
</section>
