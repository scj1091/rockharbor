var RH = RH || {};

/**
 * iPhones don't let anything over videos, so we have to hide videos when menu
 * dropdowns are clicked
 */
RH.hideVideos = function() {
	jQuery('video').each(function() {
		jQuery(this).data('hidden', true);
		jQuery(this).hide();
	});
}

RH.showVideos = function() {
	jQuery('video').each(function() {
		if (jQuery(this).data('hidden') === true) {
			jQuery(this).show();
		}
	});
}