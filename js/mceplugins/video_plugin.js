(function() {
	tinymce.create('tinymce.plugins.videoShortcode', {
		init : function(ed, url) {
			ed.addCommand('videoShortcode', function() {
				RH.showMediaLibrary(function(html) {
					var url = jQuery(html).attr('href');
					var tagtext = '[videoplayer src="'+url+'"]';
					return tagtext;
				});
			});

			// Register example button
			ed.addButton('videoShortcode', {
				title : 'Video Shortcode',
				cmd : 'videoShortcode',
				image : url + '/video.png'
			});
		}
	});

	// Register plugin
	tinymce.PluginManager.add('videoShortcode', tinymce.plugins.videoShortcode);
})();
