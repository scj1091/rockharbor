/* These scripts are for everything IE 8 and below */

(function($) {
	$(document).ready(function() {
		// fix for lack of nth-child support
		$('#access ul li div.submenu.cols2 ul:nth-child(2n+2)').addClass('nthreset');
		$('#access ul li div.submenu.cols2 ul:nth-child(3n+3)').addClass('nthreset');
	});
}(jQuery))
