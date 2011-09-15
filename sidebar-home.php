<?php 
global $theme; 
$twitteruser = $theme->options('twitter_user');
?>
<div id="secondary" class="home-widget-area">
	<div class="title"><h3>Facebook</h3><a class="title-icon" target="_blank" href="http://facebook.com">Visit Facebook</a></div>
	<div class="body">
		<p>Some facebook crap</p>
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
		<p>Some form crap</p>
	</div>
	<div class="title white reverse"><h3>Calendar</h3><a class="title-icon" target="_blank" href="http://core.rockharbor.org">Visit CORE</a></div>
	<div class="body">
		<p>Some calendar crap</p>
	</div>
</div>