var RH = RH || {};

/**
 * State-tracking to prevent memory leaks and reapplied effects
 */
RH.inTablet = false;
RH.inMobile = false;

/**
 * Called when entering the tablet breakpoint
 */
RH.tabletEnter = function() {
	if (RH.inTablet) {
		return;
	}
	RH.inTablet = true;
	// make nav the first element
	jQuery('nav#access').insertBefore('#branding');
	// make footer touch-friendly
	jQuery('footer .tabs a').on('click', function() {
		jQuery('footer .tabs a').removeClass('selected');
		jQuery('#footer > div').hide();
		var selector = jQuery(this).data('tab');
		jQuery(selector).show();
		jQuery(this).addClass('selected');
	});
	jQuery('footer .tabs a:first-child').click();
	// for you people resizing the browser :)
	RH.hideMenuOptions();
}

/**
 * Called when exiting the tablet breakpoint
 */
RH.tabletExit = function() {
	if (!RH.inTablet) {
		return;
	}
	RH.inTablet = false;
	// make nav the last element
	jQuery('nav#access').insertAfter('#branding');
	// reset footer
	jQuery('#footer > div').show();
	jQuery('footer .tabs a').off('click');
}

/**
 * Called when entering the mobile breakpoint
 */
RH.mobileEnter = function() {
	if (RH.inMobile) {
		return;
	}
	RH.inMobile = true;
	jQuery('.global-navigation li.menu').on('click', function() {
		RH.hideMenuOptions('menu');
		jQuery('#access').slideToggle();
	});
	jQuery('.global-navigation li.campuses').on('click', function() {
		RH.hideMenuOptions('campuses');
		jQuery(this).children('ul').toggle();
	});
	jQuery('.global-navigation li.search').on('click', function() {
		RH.hideMenuOptions('search');
		jQuery(this).children('form').toggle();
	});
	jQuery('.global-navigation li.search form').on('click', function(event) {
		// prevent clicking on input from closing the search form
		event.stopPropagation();
	});
}

/**
 * Called when exiting the mobile breakpoint
 */
RH.mobileExit = function() {
	if (!RH.inMobile) {
		return;
	}
	RH.inMobile = false;
	jQuery('.global-navigation li.menu').off('click');
	jQuery('.global-navigation li.campuses').off('click');
	jQuery('.global-navigation li.search').off('click');
}

RH.hideMenuOptions = function(keep) {
	if (keep !== 'menu') {
		jQuery('#access').slideUp(function() {
			jQuery(this).css('display', '');
		});
	}
	if (keep !== 'campuses') {
		jQuery('.global-navigation li.campuses ul').hide(function() {
			jQuery(this).css('display', '');
		});
	}
	if (keep !== 'search') {
		jQuery('.global-navigation li.search form').hide(function() {
			jQuery(this).css('display', '');
		});
	}
}