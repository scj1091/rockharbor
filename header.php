<?php global $post, $theme; ?>
<?php get_template_part('header', 'prebody') ?>
<body <?php body_class(); ?>>

	
	<div id="page" class="hfeed">

		<?php
		echo $theme->render('global_navigation');
		?>
		
		<header id="branding" role="banner">
			<div id="banner" class="clearfix"><?php echo $theme->render('banner'); ?></div>
			<nav id="access" role="navigation" class="clearfix">
				<?php wp_nav_menu(array('theme_location' => 'main', 'menu_class' => 'menu clearfix')); ?>
			</nav>
		</header>

		<?php if (isset($_GET['message'])): ?>
		<div class="message"><?php echo $_GET['message']; ?></div>
		<?php endif; ?>
		
		<?php if (!empty($post->post_parent)): ?>
		<nav class="breadcrumb">
			<?php
			$ancestors = get_post_ancestors($post->ID);
			$ancestors = array_reverse($ancestors);
			$links = array();
			$sep = '&nbsp;<span class="secondary">&gt;</span>&nbsp;';
			foreach ($ancestors as $ancestor) {
				$ancestorPost = get_post($ancestor);
				$links[] = $theme->Html->tag('a', $ancestorPost->post_title, array(
					'href' => get_permalink($ancestorPost->ID),
					'title' => esc_attr($ancestorPost->post_title)
				));
			}
			$links[] = $post->post_title;
			
			echo implode($sep, $links);
			?>
		</nav>
		<?php endif; ?>

		<div id="main" class="clearfix">