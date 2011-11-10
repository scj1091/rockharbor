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

$file = $enclosure[0];
$file = str_replace(array("\r\n", "\r", "\n"), '', $file);
$tantan = get_option('tantan_wordpress_s3', false);
if ($tantan && !empty($tantan['bucket'])) {
	$file = str_replace('http://'.$tantan['bucket'].'.s3.amazonaws.com/', '', $file);
}

$flashvars = array(
	'controllbar' => 'over',
	'file' => $file,
	'autostart' => 'false',
	'skin' => $theme->info('base_url').'/swf/rhskin.zip',
	'provider' => 'rtmp',
	'streamer' => 'rtmp://'.$streamer.'.cloudfront.net/cfx/st'
);
if (has_post_thumbnail()) {
	$attach_id = get_post_thumbnail_id($post->ID);
	$attach = wp_get_attachment_image_src($attach_id, 'large');
	$flashvars['image'] = $attach[0];
}
// doesn't really matter as it will be sized with jQuery().fitVids()
$width = 500;
$height = $width*9/16;

$id = uniqid();

$flashvarsurl = array();
foreach ($flashvars as $var => $val) {
	$flashvarsurl[] = $var.'='.urlencode($val);
}
$flashvarsurl = implode('&', $flashvarsurl);
?>
<div class="embedded-video" id="player-<?php echo $id;?>">
	<object id="embedded-video-<?php echo $id; ?>" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
		<param name="movie" value="<?php echo $theme->info('base_url'); ?>/swf/player.swf" />
		<param name="allowfullscreen" value="true" />
		<param name="allowscriptaccess" value="always" />
		<param name="flashvars" value="<?php echo $flashvarsurl; ?>" />
		<param name="wmode" value="transparent" /> 
		<!--[if !IE]>-->
		<object type="application/x-shockwave-flash" data="<?php echo $theme->info('base_url'); ?>/swf/player.swf"  width="<?php echo $width; ?>" height="<?php echo $height; ?>">
			<param name="allowfullscreen" value="true" />
			<param name="allowscriptaccess" value="always" />
			<param name="flashvars" value="<?php echo $flashvarsurl; ?>" />
			<param name="wmode" value="transparent" /> 
		<!--<![endif]-->
			<a href="http://www.adobe.com/go/getflashplayer">
				<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
			</a>
		<!--[if !IE]>-->
		</object>
		<!--<![endif]-->
	</object>
</div>
<div id="debug"></div>
<script type='text/javascript'>
	 swfobject.registerObject("embedded-video-<?php echo $id; ?>", "9.0.115", "expressInstall.swf");
</script>