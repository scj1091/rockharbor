(function() {
	tinymce.create('tinymce.plugins.videoShortcode', {
		init : function(ed, url) {
			ed.addCommand('videoShortcode', function() {
				ed.windowManager.open({
					file : url + '/video-window.php',
					width : 360,
					height : 210,
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
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
