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