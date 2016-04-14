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

RH.swapSvg = function() {
	jQuery('img[src*="svg"]').attr('src', function() {
		jQuery('.rockharbor-logo-svg').addClass('rockharbor-logo').removeClass('rockharbor-logo-svg');
		return jQuery(this).removeClass('svg-logo').attr('src').replace('.svg', '.png');
	});
}

// initialized on every page
jQuery(document).ready(function() {

	jQuery('#simple-menu').sidr({
		name: 'campus-menu',
		displace: false
	});

    jQuery('.frontpage-banner').slick({dots: true});
    jQuery('.global-maps').slick({dots: true});

    jQuery('.faq-slides').slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        dots: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }
        ]
    });

    jQuery('.icon-close, .main-content').on('click', function(){
        jQuery.sidr('close', 'campus-menu');
    });

	// Add 'has_children' class to menus
	jQuery.each(jQuery('.menu-item').has('.submenu'), function() {
		jQuery(this).addClass('has_children');
	});

	// put galleries in lightboxes
	jQuery('.gallery').each(function() {
		var id = jQuery(this).attr('id');
		jQuery(this).find('.gallery-icon a').attr('rel', 'lightbox['+id+']');
	});
	doLightBox();

	jQuery('.flash-message').delay(5000).slideUp();
	jQuery('img')
		.removeAttr('width')
		.removeAttr('height');

	var preserveAspectRatio = function(container) {
		var element = jQuery(container).find('video');
		if (element.length === 0 || typeof jQuery(element)[0].player === 'undefined') {
			return;
		}
		var player = jQuery(element)[0].player;
		var w = jQuery(container).width();
		var h = w*9/16;
		player.setPlayerSize(w, h);
	}

	// improve media elements
	jQuery('video')
		.mediaelementplayer({
			pluginPath: RH.base_url+'/swf/',
			flashName: 'flashmediaelement.2.18.2.swf',
			silverlightName: 'silverlightmediaelement.2.18.2.xap',
			success: function(media, node) {
				if (media.pluginType !== 'native' && jQuery(node).attr('data-streamfile')) {
					media.setSrc(jQuery(node).attr('data-streamfile'));
					media.load();
				}
			}
		});
	jQuery('audio').mediaelementplayer({
		pluginPath: RH.base_url+'/swf/',
		flashName: 'flashmediaelement.2.18.2.swf',
		silverlightName: 'silverlightmediaelement2.18.2.xap',
		audioWidth: 200,
		audioHeight: 20,
		features: ['playpause', 'progress', 'current']
	});

	if (jQuery('video').length > 0) {
		jQuery(window).resize(function() {
			jQuery('.embedded-video').each(function() {
				preserveAspectRatio(this);
			});
		});
	}

	jQuery(window).resize();

	// responsive breakpoints
	mediaCheck({
		media: '(max-width: 569px)',
		entry: RH.mobileEnter,
		exit: RH.mobileExit
	});
	mediaCheck({
		media: '(max-width: 1025px)',
		entry: RH.tabletEnter,
		exit: RH.tabletExit
	});

	jQuery('#access > ul > li').hover(function() {
		var menu = jQuery(this).children('.submenu');
		if (menu.length === 0) {
			return;
		}
		var pos = jQuery(this).position();
		var width = jQuery(window).width();
		if (pos.left + menu.width() > width) {
			menu.css({
				right: 0
			});
		}
	});


	// implement super sonic hyper-responsive clicking for touchscreens
	new FastClick(document.body);

	if (!document.implementation.hasFeature('http://www.w3.org/TR/SVG11/feature#Image', '1.1')) {
		RH.swapSvg();
	}
});
