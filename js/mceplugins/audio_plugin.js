(function() {
	tinymce.create('tinymce.plugins.audioShortcode', {
		init : function(ed, url) {
			ed.addCommand('audioShortcode', function() {
				RH.showMediaLibrary(function(html) {
					var url = jQuery(html).attr('href');
					var tagtext = '[audioplayer src="'+url+'"]';
					return tagtext;
				});
			});

			// Register example button
			ed.addButton('audioShortcode', {
				title : 'Audio Shortcode',
				cmd : 'audioShortcode',
				image : url + '/audio.png'
			});
		}
	});

	// Register plugin
	tinymce.PluginManager.add('audioShortcode', tinymce.plugins.audioShortcode);
})();
