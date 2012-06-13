<?php
global $post, $theme;

// find the enclosure - gets the first video enclosure
$enclosure = get_post_meta($post->ID, 'enclosure');
$file = null;
if (!empty($enclosure)) {
	foreach ($enclosure as $enclosed) {
		$enclosedSplit = explode("\n", $enclosed);
		if (!empty($enclosedSplit[2]) && strpos($enclosedSplit[2], 'video/') !== false) {
			$file = $enclosedSplit[0];
			break;
		}
	}
	if (empty($file)) {
		return null;
	}
} else {
	return null;
}

$file = str_replace(array("\r\n", "\r", "\n"), '', $file);

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