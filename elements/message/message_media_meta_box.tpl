<div class="meta-section">
<p>
	<strong>Hide this post from the Message Archive</strong><br />
	<?php
	echo $theme->Html->input('hide_from_message_archive', array(
		'type' => 'checkbox',
		'label' => 'Hide post',
		'div' => false,
		'value' => 1
	));
	echo '<br />'; ?>
</p>
<p>
	<strong>Video Message URL</strong><br />
	<a id="media_meta_video" title="Video Message" class="mceButton mceButtonEnabled" href="javascript:;" role="button" tabindex="-1" style="text-decoration: none;">
		<img alt="Video Message" src="<?php echo $theme->info('base_url'); ?>/js/mceplugins/video.png" class="mceIcon" />
	</a>
	<?php
	echo $theme->Html->input('video_url', array(
		'label' => false,
		'div' => false,
		'size' => 120
	));
	?>
</p>
<p>
	<?php
	echo $theme->Html->input('vimeo_url', array(
		'type' => 'checkbox',
		'label' => 'Check if Vimeo URL (Amazon S3 if unchecked)',
		'value' => 1
	));
	echo '<br/>';
	echo $theme->Html->input('vimeo_wide', array(
		'type' => 'checkbox',
		'label' => 'Wide Format Vimeo (wider than 16:9)',
		'value' => 1
	));
	?>
</p>
</div>
<p>
	<strong>Audio Message URL</strong><br />
	<a id="media_meta_audio" title="Audio Message" class="mceButton mceButtonEnabled" href="javascript:;" role="button" tabindex="-1" style="text-decoration: none;">
		<img alt="Audio Message" src="<?php echo $theme->info('base_url'); ?>/js/mceplugins/video.png" class="mceIcon" />
	</a>
	<?php
	echo $theme->Html->input('audio_url', array(
		'label' => false,
		'div' => false,
		'size' => 120
	));
	?>
</p>
<script type="text/javascript">
	jQuery('#media_meta_video').click(function() {
		RH.showMediaLibrary(function(html) {
			var url = jQuery(html).attr('href');
			jQuery('#metavideourl').val(url);
			return '';
		});
	});
	jQuery('#media_meta_audio').click(function() {
		RH.showMediaLibrary(function(html) {
			var url = jQuery(html).attr('href');
			jQuery('#metaaudiourl').val(url);
			return '';
		});
	})
</script>
<style>
	#metavideourl, #metaaudiourl {
		width: 98%;
	}
	.meta-section {
		border-bottom: 1px solid #eee;
	}
</style>
