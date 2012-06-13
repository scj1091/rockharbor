<?php
global $post, $theme;

$file = $theme->getEnclosure('audio');
if (empty($file)) {
	return null;
}

$id = uniqid();
?>
<div class="embedded-audio" id="player-<?php echo $id;?>">
	<audio
		src="<?php echo $file; ?>"
		preload="none"
	></audio>
</div>