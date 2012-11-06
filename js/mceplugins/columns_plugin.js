(function() {
	tinymce.create('tinymce.plugins.columns', {
		init : function(ed, url) {
			var columnHtml = '<img src="' + url + '/trans.gif" class="rh-mce-column mceItemNoResize" title="Columns" />';
			
			// load css
			ed.onInit.add(function() {
				ed.dom.loadCSS(url + '/plugin.css');
			});
			
			ed.addCommand('columns', function() {
				RH.insertIntoEditor(columnHtml);
			});

			// Register example button
			ed.addButton('columns', {
				title : 'Split into Columns',
				cmd : 'columns',
				image : url + '/columns.gif'
			});
			
			// Replace html with visual
			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = o.content.replace(/<!--column(.*?)-->/g, columnHtml);
			});

			// Replace visual with html
			ed.onPostProcess.add(function(ed, o) {
				if (o.get)
					o.content = o.content.replace(/<img[^>]+>/g, function(im) {
						if (im.indexOf('class="rh-mce-column') !== -1) {
							im = '<!--column-->';
						}
						return im;
					});
			});
		}
	});

	// Register plugin
	tinymce.PluginManager.add('columns', tinymce.plugins.columns);
})();
