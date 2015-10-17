/**
 * ROCKHARBOR js object namespace
 *
 * @var hash
 */
var RH = {};

/**
 * Shows the media library to allow a user to select a media item
 *
 * @param callback function The callback function that's called when an item
 * is "Inserted into the post." The link WordPress provides will be passed as
 * the first argument
 * @return void
 */
RH.showMediaLibrary = function(callback) {
	tb_show('', 'media-upload.php?type=image&post_id=0&TB_iframe=true');
	window.send_to_editor = function(html) {
		var ret = callback(html);
		RH.insertIntoEditor(ret);
		tb_remove();
	};
}

/**
 * Inserts text into the editor where the cursor is
 *
 * @param text string The text to insert
 * @return void
 */
RH.insertIntoEditor = function(text) {
	if (window.tinyMCE) {
		if (window.tinyMCE.majorVersion >= "4") { //api 4 changed the function name and signature
			window.tinyMCE.execCommand('mceInsertContent', false, text);
		} else {
			window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, text);
		}
	}
}

/**
 * Adds a service time input to the list
 */
RH.addServiceTime = function() {
	var st = jQuery('.service-times div:first-child').clone();
	st.children('input').val('');
	st.children('a').show();
	jQuery('.service-times div:last').after(st);
}

/**
 * Removes a service time input
 */
RH.removeServiceTime = function(el) {
	jQuery(el).parent('div').remove();
}

/**
 * Adds an image input to the list
 *
 * @param string id The list id
 */
RH.addImageGridImage = function(id) {
	var st = jQuery('#'+id+' p:first-child').clone();
	st.children('input.img-src').val('');
	st.children('a').show();
	jQuery('#'+id+' p:last').after(st);
}

/**
 * Removes an image input
 *
 * @param string el The `<a>` element
 */
RH.removeImageGridImage = function(el) {
	jQuery(el).parent('p').remove();
}