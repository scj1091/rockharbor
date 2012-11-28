<?php 
global $theme; 
$twitteruser = $theme->options('twitter_user');
$fbuser = $theme->options('facebook_user');
$coreid = $theme->options('core_id');
$mailchimp = $theme->options('mailchimp_id');
$feedburnerid = $theme->options('feedburner_main');
$ebulletinpage = $theme->options('ebulletin_archive_page_id');
?>
	</div>
</div>
<footer role="contentinfo">
	<div class="tabs clearfix">
		<a href="#footer" data-tab="#footer .first" class="icon-message"></a>
		<a href="#footer" data-tab="#footer .second" class="icon-more"></a>
		<a href="#footer" data-tab="#footer .third" class="icon-connect"></a>
		<a href="#footer" data-tab="#footer .last" class="icon-campus"></a>
	</div>
	<div id="footer">
		<div class="first">
			<h3>Send Us a Message</h3>
			<?php
			echo $theme->render('quick_contact');
			?>
		</div>
		<div class="second">
			<h3>More</h3>
			<?php wp_nav_menu(array('theme_location' => 'footer', 'menu_class' => 'menu', 'fallback_cb' => create_function('', 'return;'))); ?>
		</div>
		<div class="third">
			<h3>Connect</h3>
			<p class="icons">
				<?php if (!empty($feedburnerid)): ?>
				<a href="http://feeds.feedburner.com/<?php echo $feedburnerid; ?>"><?php echo $theme->Html->image('rss-footer-icon.png', array('parent' => true)); ?></a>
				<?php else: ?>
				<a href="<?php bloginfo('rss2_url'); ?>"><?php echo $theme->Html->image('rss-footer-icon.png', array('parent' => true)); ?></a>
				<?php endif; ?>
				<a target="_blank" href="http://facebook.com/<?php echo $fbuser; ?>"><?php echo $theme->Html->image('facebook-footer-icon.png', array('parent' => true)); ?></a>
				<a target="_blank" href="http://twitter.com/<?php echo $twitteruser; ?>"><?php echo $theme->Html->image('twitter-footer-icon.png', array('parent' => true)); ?></a>
				<a target="_blank" href="https://core.rockharbor.org/campuses/view/Campus:<?php echo $coreid; ?>"><?php echo $theme->Html->image('core-footer-icon.png', array('parent' => true)); ?></a>
			</p>
			<hr />
			<?php if (!empty($mailchimp)): ?>
			<h3>ebulletin</h3>
			<form action="http://rockharbor.us4.list-manage.com/subscribe/post?u=185dbb9016568292b89c8731c&amp;id=06151f2612" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
				<input placeholder="email address" type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" style="width: 80%;margin-right:1%">
				<input type="hidden" value="<?php echo $mailchimp; ?>" name="group[405][<?php echo $mailchimp; ?>]">
				<input type="submit" value="Go" name="subscribe" id="mc-embedded-subscribe" class="button" style="width: 16%">
			</form>
			<?php if (!empty($ebulletinpage)) {
				echo $theme->Html->tag('a', 'View archive', array(
					'href' => get_permalink($ebulletinpage)
				));
			}
			?>
			<hr />
			<?php endif; ?>
			<p>
				3095 Redhill Ave.<br />Costa Mesa, CA 92626<br />(714) 384-0914
			</p>
		</div>
		<div class="last">
			<h3>Campuses</h3>
			<?php
			echo $theme->render('campus_menu');
			?>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>