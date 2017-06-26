/* These scripts are for everything IE 8 and below */

(function($) {
	$(document).ready(function() {
		// fix for lack of nth-child support
		$('#access ul li div.submenu.cols2 ul:nth-child(2n+2)').addClass('nthreset');
		$('#access ul li div.submenu.cols2 ul:nth-child(3n+3)').addClass('nthreset');
		$(".stories-2 .story-box:nth-child(2n+2)").addClass('marginreset');
		$(".stories-3 .story-box:nth-child(3n+3)").addClass('marginreset');
		$(".stories-4 .story-box:nth-child(4n+4)").addClass('marginreset');
		$(".stories-2 .story-box:nth-child(2n+3)").addClass('nthreset');
		$(".stories-3 .story-box:nth-child(3n+4)").addClass('nthreset');
		$(".stories-4 .story-box:nth-child(4n+5)").addClass('nthreset');
		$("#content .series-collection article.message-series:nth-child(4n+5)").addClass('nthreset');
		$(".page-template #content article.staff:nth-child(6n+7)").addClass('nthreset');
	});
}(jQuery))
