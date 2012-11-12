var RH = RH || {};

/**
 * Called when entering the tablet breakpoint
 */
RH.tabletEnter = function() {
	// make nav the first element
	jQuery('#branding nav').detach().prependTo('#branding');
}

/**
 * Called when exiting the tablet breakpoint
 */
RH.tabletExit = function() {
	// make nav the last element
	jQuery('#branding nav').detach().appendTo('#branding');
}

/**
 * Called when entering the mobile breakpoint
 */
RH.mobileEnter = function() {
	jQuery('#footer .tabs a').on('click', function() {
		jQuery('#footer .tabs a').removeClass('selected');
		jQuery('#footer .column > div').hide();
		var selector = $(this).data('tab');
		$(selector).show();
		$(this).addClass('selected');
	});
	jQuery('#footer .tabs a:first-child').click();
}

/**
 * Called when exiting the mobile breakpoint
 */
RH.mobileExit = function() {
	jQuery('#footer .column > div').show();
	jQuery('#footer .tabs a').off('click');
}