<?php
global $theme;

if (!isset($src)) {
	$src = $theme->getEnclosure('audio');
}
if (empty($src)) {
	return null;
}

// check if we should pull streaming info from another blog
if (!isset($campus) || empty($campus)) {
	$campus = $theme->info('id');
}

$streamer = $theme->options('s3_streamer', false, $campus);
$downloader = $theme->options('s3_download', false, $campus);
$bucket = $theme->options('s3_bucket', false, $campus);

$id = uniqid();
?>
<div class="embedded-audio" id="player-<?php echo $id;?>">
	<audio
		src="<?php
		$srcFile = $src;
		if (!empty($downloader)) {
			$srcFile = str_replace("http://$bucket.s3.amazonaws.com/", "http://$downloader/", $src);
		}
		echo $srcFile;
		?>"
		preload="none"
		<?php
		if (!empty($streamer)) {
			if (stripos($src, $bucket) !== false) {
				$filename = str_replace("http://$bucket.s3.amazonaws.com/", 'mp4:', $src);
				echo " data-streamfile=\"rtmp://$streamer/$filename\"";
			}
		}
		?>
	></audio>
</div>