<?php
global $post, $theme;

$file = $theme->getEnclosure('video');
if (empty($file)) {
	return null;
}

$poster = null;
if (has_post_thumbnail()) {
	$attach_id = get_post_thumbnail_id($post->ID);
	$attach = wp_get_attachment_image_src($attach_id, 'large');
	$poster = $attach[0];
}

$id = uniqid();
?>
<div class="embedded-video" id="player-<?php echo $id;?>">
	<video 
		id="embedded-player-<?php echo $id;?>"
		src="<?php echo $file; ?>"
		controls
		preload="auto"
		width="100%"
		height="100%"
		<?php
		if (!empty($poster)) {
			echo 'poster="'.$poster.'"';
		}
		?>
	></video>
</div>