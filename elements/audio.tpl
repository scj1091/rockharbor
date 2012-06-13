<?php
global $post, $theme;

// find the enclosure - gets the first video enclosure
$enclosure = get_post_meta($post->ID, 'enclosure');
$file = null;
if (!empty($enclosure)) {
	foreach ($enclosure as $enclosed) {
		$enclosedSplit = explode("\n", $enclosed);
		if (!empty($enclosedSplit[2]) && strpos($enclosedSplit[2], 'audio/') !== false) {
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
$id = uniqid();
?>
<div class="embedded-audio" id="player-<?php echo $id;?>">
	<audio
		src="<?php echo $file; ?>"
		preload="none"
	></audio>
</div>