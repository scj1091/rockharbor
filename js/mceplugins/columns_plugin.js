tinymce.PluginManager.add('columns', function(editor, url) {
	var columnHtml = '<img src="' + url + '/trans.gif" class="rh-mce-column mceItemNoResize" title="Columns" />';

	// load css
	editor.on('init', function(e) {
		editor.dom.loadCSS(url + '/plugin.css');
	});

	// add command
	editor.addCommand('columns', function() {
		RH.insertIntoEditor(columnHtml);
	});

	// add button
	editor.addButton('columns', {
		title: 'Split into Columns',
		cmd: 'columns',
		image: url + '/columns.gif'
	});

	// replace html with visual
	editor.on('BeforeSetContent', function(e) {
		e.content = e.content.replace(/<!--column(.*?)-->/g, columnHtml);
	});

	// replace visual with html
	editor.on('PostProcess', function(e) {
		if (e.get) {
			e.content = e.content.replace(/<img[^>]+>/g, function(im) {
				if (im.indexOf('class="rh-mce-column') !== -1) {
					im = '<!--column-->';
				}
				return im;
			});
		}
	});
});