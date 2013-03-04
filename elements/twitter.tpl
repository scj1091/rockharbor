<?php
if (!isset($term)) {
	$term = 'none';
}
if (!isset($limit)) {
	$limit = 5;
}
$results = $this->Twitter->fetchFeed($term, $limit);
if (count($results)):
for ($t=0; $t<$limit; $t++): $item = $results[$t]; if (!$item) { continue; } ?>
<div class="twitter-item clearfix">
	<div class="twitter-author-image">
		<a target="_blank" href="http://www.twitter.com/<?php echo $item['user']['screen_name']; ?>" border="0">
			<img src="<?php echo $item['user']['profile_image_url'];?>" alt="<?php echo $item['user']['screen_name']; ?>" />
		</a>
	</div>
	<div class="twitter-copy">
		<div class="twitter-author">
			<a target="_blank" href="http://www.twitter.com/<?php echo $item['user']['screen_name']; ?>">@<?php echo $item['user']['screen_name'];?></a>
		</div>
		<div class="twitter-tweet"><?php
		$item['text'] = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $item['text']);
		$item['text'] = preg_replace('/(@)(\w+)/', '<a href="http://twitter.com/$2">$1$2</a>', $item['text']);
		echo $item['text'];
		?></div>
		<div class="twitter-time">
			<a target="_blank" href="http://twitter.com/#!/<?php echo $item['user']['screen_name']; ?>/status/<?php echo $item['id_str']; ?>">
			<?php echo human_time_diff(strtotime($item['created_at']));?> ago
			</a>
		</div>
	</div>
</div>
<?php endfor; ?>
<?php else: ?>
<p>There are no tweets yet!</p>
<?php endif; ?>