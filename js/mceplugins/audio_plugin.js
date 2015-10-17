tinymce.PluginManager.add('audioShortcode', function(editor, url) {
	//add shortcode command
	editor.addCommand('audioShortcode', function() {
		RH.showMediaLibrary(function(html) {
			var url = jQuery(html).attr('href');
			var tagtext = '[audioplayer src="'+url+'"]';
			return tagtext;
		});
	});

	// add toolbar button
	editor.addButton('audioShortcode', {
		title: 'Audio Shortcode',
		cmd: 'audioShortcode',
		image: url + '/audio.png'
	});
});