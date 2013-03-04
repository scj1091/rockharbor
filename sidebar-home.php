<?php
global $theme;
$twitteruser = $theme->options('twitter_user');
$fbuser = $theme->options('facebook_user');
$coreid = $theme->options('core_id');
$core_calendar_id = $theme->options('core_calendar_id');
?>
<div id="pre-secondary">
	<?php
	$theme->set('user', $fbuser);
	echo $theme->render('facebook');
	?>
</div>
<div id="secondary" class="home-widget-area">
	<div class="title"><h3><?php echo $theme->Html->image('twitter-icon.png'); ?>Twitter</h3><a class="title-icon" target="_blank" href="http://twitter.com/<?php echo $twitteruser; ?>"><?php echo $theme->Html->image('out.png'); ?></a></div>
	<div class="body">
		<?php
		$theme->set('term', $theme->options('twitter_search'));
		echo $theme->render('twitter');
		?>
	</div>
	<div class="title white"><h3><?php echo $theme->Html->image('story-icon.png', array('parent' => true)); ?>Share Your Story</h3></div>
	<div class="body">
		<?php
		echo $theme->render('share_your_story');
		?>
	</div>
	<div class="title white reverse"><h3><?php echo $theme->Html->image('calendar-icon.png', array('parent' => true)); ?>Calendar</h3>
		<?php
		$calendarpageid = $theme->options('calendar_page_id');
		if (empty($calendarpageid)) {
			$link = "https://core.rockharbor.org/campuses/view/Campus:$coreid";
			$target = ' target="_blank"';
		} else {
			$link = get_permalink($calendarpageid);
			$target = null;
		}
		?>
		<a class="title-icon"<?php echo $target; ?> href="<?php echo $link; ?>"><?php echo $theme->Html->image('out-grey.png', array('parent' => true)); ?></a>
	</div>
	<div class="body">
		<?php
		$theme->set('events', $theme->fetchCoreFeed($core_calendar_id));
		echo $theme->render('core_public_calendar');
		?>
	</div>
</div>