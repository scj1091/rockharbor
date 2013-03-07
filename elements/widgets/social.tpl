<?php
if (!isset($term)) {
	$term = 'none';
}
if (!isset($limit)) {
	$limit = 5;
}
if (count($tweets)):
foreach ($tweets as $item):
?>
<div class="twitter-item clearfix">
	<div class="twitter-author-image">
		<a target="_blank" href="http://www.twitter.com/<?php echo $item['user']['screen_name']; ?>" border="0">
			<img src="<?php echo $item['user']['profile_image_url'];?>" alt="<?php echo $item['user']['screen_name']; ?>" />
		</a>
	</div>
	<div class="twitter-copy">
		<p class="twitter-author">
			<a target="_blank" href="http://www.twitter.com/<?php echo $item['user']['screen_name']; ?>">@<?php echo $item['user']['screen_name'];?></a>
		</p>
		<p class="twitter-tweet"><?php
		$item['text'] = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $item['text']);
		$item['text'] = preg_replace('/(@)(\w+)/', '<a href="http://twitter.com/$2">$1$2</a>', $item['text']);
		echo $item['text'];
		?></p>
		<p class="twitter-time">
			<a target="_blank" href="http://twitter.com/#!/<?php echo $item['user']['screen_name']; ?>/status/<?php echo $item['id_str']; ?>">
			<?php echo human_time_diff(strtotime($item['created_at']));?> ago
			</a>
		</p>
	</div>
</div>
<?php endforeach; ?>
<?php else: ?>
<p>There are no tweets yet!</p>
<?php endif; ?>
<?php if (!empty($facebookResults)): ?>
<p>Connect with us on <a href="http://facebook.com/<?php echo $facebookUser; ?>">Facebook</a> or like us below!</p>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like" data-href="http://www.facebook.com/<?php echo $facebookUser; ?>" data-send="false" data-layout="button_count" data-width="300" data-show-faces="false"></div>
<?php endif; ?>
