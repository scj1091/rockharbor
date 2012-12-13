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
	tb_show('', 'media-upload.php?type=image&TB_iframe=true');
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
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, text);
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