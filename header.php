<?php global $theme; ?>
<?php get_template_part('header', 'prebody') ?>
<body <?php body_class(); ?>>

	
	<div id="page" class="hfeed">

		<?php
		echo $theme->render('global_navigation');
		?>
		
		<header id="branding" role="banner">
			<nav id="access" role="navigation" class="clearfix">
				<?php wp_nav_menu(array('theme_location' => 'main', 'menu_class' => 'menu clearfix')); ?>
			</nav>
		</header>


		<div id="main" class="clearfix">