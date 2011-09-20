<?php 
global $theme; 
$twitteruser = $theme->options('twitter_user');
$fbuser = $theme->options('facebook_user');
$coreid = $theme->options('core_id');
?>
<div id="secondary" class="home-widget-area">
	<div class="title"><h3>Facebook</h3><a class="title-icon" target="_blank" href="http://facebook.com/<?php echo $fbuser; ?>">Visit Facebook</a></div>
	<div class="body">
		<?php
		$theme->set('user', $fbuser);
		echo $theme->render('facebook');
		?>
	</div>
	<div class="title reverse"><h3>Twitter</h3><a class="title-icon" target="_blank" href="http://twitter.com/<?php echo $twitteruser; ?>">Visit Twitter</a></div>
	<div class="body">
		<?php 
		$theme->set('term', $theme->options('twitter_search'));
		echo $theme->render('twitter'); 
		?>
	</div>
	<div class="title white"><h3>Share Your Story</h3></div>
	<div class="body">
		<?php
		echo $theme->render('share_your_story');
		?>
	</div>
	<div class="title white reverse"><h3>Calendar</h3><a class="title-icon" target="_blank" href="http://core.rockharbor.org/ministries/<?php echo $coreid; ?>">Visit CORE</a></div>
	<div class="body">
		<?php
		$theme->set('events', $theme->getCoreHomepageEvents($coreid));
		echo $theme->render('core_public_calendar');
		?>
	</div>
</div>