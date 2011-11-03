<?php 
global $theme; 
$twitteruser = $theme->options('twitter_user');
$fbuser = $theme->options('facebook_user');
$coreid = $theme->options('core_id');
?>
	</div>
</div>
<footer role="contentinfo">
	<div id="footer">
		<div class="first">
			<h3>Prayer Requests</h3>
			<?php
			echo $theme->render('prayer_request');
			?>
		</div>
		<div>
			<h3>More</h3>
			<?php wp_nav_menu(array('theme_location' => 'footer', 'menu_class' => 'menu', 'fallback_cb' => function() { })); ?>
		</div>
		<div>
			<h3>Connect</h3>
			<p class="icons">
				<a href="<?php bloginfo('rss2_url'); ?>"><?php echo $theme->Html->image('rss-footer-icon.png', array('parent' => true)); ?></a>
				<a target="_blank" href="http://facebook.com/<?php echo $fbuser; ?>"><?php echo $theme->Html->image('facebook-footer-icon.png', array('parent' => true)); ?></a>
				<a target="_blank" href="http://twitter.com/<?php echo $twitteruser; ?>"><?php echo $theme->Html->image('twitter-footer-icon.png', array('parent' => true)); ?></a>
				<a target="_blank" href="https://core.rockharbor.org/ministries/<?php echo $coreid; ?>"><?php echo $theme->Html->image('core-footer-icon.png', array('parent' => true)); ?></a>
			</p>
			<hr />
			<p>
				3095 Redhill Ave.<br />Costa Mesa, CA 92626<br />(714) 384-0914
			</p>
		</div>
		<div class="last">
			<h3>Campuses</h3>
			<?php
			$theme->set('reverse', true);
			echo $theme->render('campus_menu');
			?>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>