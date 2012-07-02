<?php
global $post;
if (!isset($page) || empty($page)) {
	$page = $_SERVER['HTTP_REFERER'];
}
if (!isset($title) || empty($title)) {
	$title = get_the_title();
}
if (!isset($message)) {
	$message = null;
}
$message = rawurlencode($message);
$page = rawurlencode($page);
$title = rawurlencode($title);
?>
<a class="share-facebook" href="http://www.facebook.com/sharer.php?u=<?php echo $page; ?>&t=<?php echo $title; ?>">
	<?php echo $theme->Html->image('facebook-icon.png', array('parent' => false, 'alt' => 'Share on Facebook')); ?>
</a>
<a class="share-twitter" href="http://twitter.com/home?status=<?php echo $message.$page; ?>" target="_blank">
	<?php echo $theme->Html->image('twitter-icon.png', array('parent' => false, 'alt' => 'Share on Twitter')); ?>
</a>
<script type="text/javascript">
	jQuery('.share-facebook, .share-twitter').click(function() {
		window.open(jQuery(this).attr('href'), 'share', 'width=500,height=400');
		return false;
	});
</script>