<?php
$theme->Html->inputPrefix = 'meta';
$theme->Html->data($data);
?>
<p>
	<strong>Document</strong><br />
	<a id="media_document" title="Document" class="mceButton mceButtonEnabled" href="javascript:;" role="button" tabindex="-1" style="text-decoration: none;">
		<img alt="Media Library" src="<?php echo $theme->info('base_url'); ?>/js/mceplugins/video.png" class="mceIcon" />
	</a>
	<?php
	echo $theme->Html->input('document_url', array(
		'label' => false,
		'div' => false,
		'size' => 120
	));
	?>
</p>
<script type="text/javascript">
	jQuery('#media_document').click(function() {
		RH.showMediaLibrary(function(html) {
			var url = jQuery(html).attr('href');
			jQuery('#metadocumenturl').val(url);
			return '';
		});
	});
</script>