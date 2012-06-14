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
	<div class="share-video">
		<a href="javascript:;" class="embed-link">Share</a>
	</div>
	<div class="share-modal">
		<a href="javascript:;" class="embed-link">X</a>
		<p>To embed this video, copy and paste this code into your website.</p>
		<textarea id="code-<?php echo $id;?>"><iframe src="<?php echo $theme->info('base_url'); ?>/embed.php?post=<?php echo $post->ID; ?>&blog=<?php echo $theme->info('id'); ?>" width="400" height="225" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></textarea>
		<label for="width-<?php echo $id;?>">Width:</label>
		<input type="text" value="400" id="width-<?php echo $id;?>" size="10" />
		<label for="height-<?php echo $id;?>">Height:</label>
		<input type="text" value="225" id="height-<?php echo $id;?>" size="10" />
	</div>
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
<script type="text/javascript">
	jQuery('#player-<?php echo $id;?> .share-video a').click(function() {
		jQuery('#player-<?php echo $id;?> .share-modal').show();
		var h = jQuery('#player-<?php echo $id;?> .share-modal').outerHeight();
		var w = jQuery('#player-<?php echo $id;?> .share-modal').outerWidth();
		jQuery('#player-<?php echo $id;?> .share-modal').hide();
		var t = (jQuery('#player-<?php echo $id;?>').height() - 30) / 2 - h/2;
		var l = jQuery('#player-<?php echo $id;?>').width() / 2 - w/2;
		jQuery('#player-<?php echo $id;?> .share-modal').css({
			top: t+'px',
			left: l+'px'
		}).fadeIn();
	});
	jQuery('#player-<?php echo $id;?> .share-modal a').click(function() {
		jQuery(this).parent().fadeOut();
	});
	jQuery('#code-<?php echo $id;?>').click(function() {
		this.focus();
		this.select();
	});
	jQuery('#width-<?php echo $id;?>, #height-<?php echo $id;?>').change(function() {
		var w, h;
		if (jQuery(this).attr('id') == 'width-<?php echo $id;?>') {
			w = jQuery(this).val();
			h = w*9/16;
		} else {
			h = jQuery(this).val();
			w = h*16/9;
		}
		
		w = Math.ceil(w);
		h = Math.ceil(h);
		
		jQuery('#width-<?php echo $id;?>').val(w);
		jQuery('#height-<?php echo $id;?>').val(h);
		
		var embed = jQuery('#code-<?php echo $id;?>').val();
		embed = embed.replace(/width="([0-9]+)"/, 'width="'+w+'"');
		embed = embed.replace(/height="([0-9]+)"/, 'height="'+h+'"');
		jQuery('#code-<?php echo $id;?>').val(embed);
	});
</script>