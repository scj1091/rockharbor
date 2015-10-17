tinymce.PluginManager.add('videoShortcode', function(editor, url) {
	// add command
	editor.addCommand('videoShortcode', function() {
		RH.showMediaLibrary(function(html) {
			var url = jQuery(html).attr('href');
			var tagtext = '[videoplayer src="'+url+'"]';
			return tagtext;
		});
	});

	// add button
	editor.addButton('videoShortcode', {
		title: 'Video Shortcode',
		cmd: 'videoShortcode',
		image: url + '/video.png'
	});
});