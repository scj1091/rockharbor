var RH = RH || {};

/**
 * Called when entering the tablet breakpoint
 */
RH.tabletEnter = function() {
	// make nav the first element
	jQuery('#branding nav').detach().prependTo('#branding');
	// make footer touch-friendly
	jQuery('footer .tabs a').on('click', function() {
		jQuery('footer .tabs a').removeClass('selected');
		jQuery('#footer > div').hide();
		var selector = $(this).data('tab');
		$(selector).show();
		$(this).addClass('selected');
	});
	jQuery('footer .tabs a:first-child').click();
	// for you people resizing the browser :)
	RH.hideMenuOptions();
}

/**
 * Called when exiting the tablet breakpoint
 */
RH.tabletExit = function() {
	// make nav the last element
	jQuery('#branding nav').detach().appendTo('#branding');
	// reset footer
	jQuery('#footer > div').show();
	jQuery('footer .tabs a').off('click');
}

/**
 * Called when entering the mobile breakpoint
 */
RH.mobileEnter = function() {
	jQuery('#global-navigation li.menu').on('click', function() {
		RH.hideMenuOptions('menu');
		jQuery('#access').slideToggle();
	});
	jQuery('#global-navigation li.campuses').on('click', function() {
		RH.hideMenuOptions('campuses');
		$(this).children('ul').toggle();
	});
	jQuery('#global-navigation li.search').on('click', function() {
		RH.hideMenuOptions('search');
		$(this).children('form').toggle();
	});
}

/**
 * Called when exiting the mobile breakpoint
 */
RH.mobileExit = function() {
	jQuery('#global-navigation li.menu').off('click');
	jQuery('#global-navigation li.campuses').off('click');
	jQuery('#global-navigation li.search').off('click');
}

RH.hideMenuOptions = function(keep) {
	if (keep !== 'menu') {
		jQuery('#access').slideUp(function() {
			$(this).css('display', '');
		});
	}
	if (keep !== 'campuses') {
		jQuery('#global-navigation li.campuses ul').hide(function() {
			$(this).css('display', '');
		});
	}
	if (keep !== 'search') {
		jQuery('#global-navigation li.search form').hide(function() {
			$(this).css('display', '');
		});
	}
}