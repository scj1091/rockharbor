<?php
global $post;

// find the enclosure
$enclosure = get_post_meta($post->ID, 'enclosure');
if (!empty($enclosure[0])) {
	$enclosure = explode("\n", $enclosure[0]);
	if (empty($enclosure[2]) || strpos($enclosure[2], 'video/') === false) {
		return null;
	}
} else {
	return null;
}

$flashvars = array(
	'controllbar' => 'over',
	'file' => str_replace(array("\r\n", "\r", "\n"), '', $enclosure[0]),
	'autostart' => 'false',
	'skin' => $theme->info('base_url').'/swf/rhskin.zip'
);
if (has_post_thumbnail()) {
	$attach_id = get_post_thumbnail_id($post->ID);
	$attach = wp_get_attachment_image_src($attach_id, 'large');
	$flashvars['image'] = $attach[0];
}
// doesn't really matter as it will be sized with jQuery().fitVids()
$width = 480;
$height = $width*9/16;

$id = uniqid('embedded-video-');
?>
<div class="embedded-video">
	<div id="<?php echo $id; ?>">Loading video...</div>
</div>
<script type='text/javascript'>
	var so = new SWFObject('<?php echo $theme->info('base_url'); ?>/swf/player.swf', 'ply', '<?php echo $width; ?>','<?php echo $height; ?>', '9', '#000000');
	so.addParam('allowfullscreen','true');
	so.addParam('allowscriptaccess','always');
	so.addParam('wmode','opaque');
	<?php foreach ($flashvars as $var => $val): ?>
	so.addVariable('<?php echo $var; ?>','<?php echo trim($val); ?>');
	<?php endforeach; ?>
	so.write('<?php echo $id; ?>');
	// remove paragraph tag that wordpress wraps the video player in
	//jQuery('.embedded-video').prev('p').remove();
</script>