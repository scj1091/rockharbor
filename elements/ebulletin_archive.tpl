<div class="ebulletins-container">
</div>
<style type="text/css">
.ebulletins-container {
	width: 100%;
	padding: 2.5em;
	background-color: #eee
}
.ebulletin-wrapper {
	height: 75px;
	background-color: #fff;
	border-right: 2px solid #ddd;
	border-bottom: 2px solid #ddd;
	padding: 2.5em;
	margin-bottom: 20px;
	transition-property: width, height;
	transition-duration: 0.7s;
	overflow: hidden;
}
.ebulletin-wrapper > div {
	font-size: 2em;
	font-family: Montserrat, Arial, sans-serif;
	margin-bottom: 0.5em;
	display: inline;
	float: left;
}
.ebulletin-wrapper > div.ebulletin-link {
	margin-left: 20px;
	display: none;
}
.ebulletin-wrapper > div:hover {
	color: #1C9DC2;
}
.ebulletin-wrapper > iframe {
	width: 100%;
	height: 100%;
}
</style>
<script language="javascript" src="http://us4.campaign-archive2.com/generate-js/?u=185dbb9016568292b89c8731c&fid=<?php echo $id; ?>&show=10" type="text/javascript"></script>
<script type="text/javascript">
var archiveContainer = jQuery('.display_archive');
var campaigns = archiveContainer.children();
var ebulletinsContainer = jQuery('div.ebulletins-container');

function generateCard(i) {
	var ebulletinsContainer = i;

	return function(index, element) {
		var anchorObj = jQuery(this).children('a');
		var href = anchorObj.attr('href');
		var innerHtml = anchorObj.html();
		var output = jQuery('<div class="ebulletin-wrapper" data-ebulletin-url="' + href + '"><div>' + innerHtml + '</div><div class="ebulletin-link"><a href="' + href + '" target="_blank">View full size</a></div></div>');
		output.on("click", function() {
			var thisObj = jQuery(this);
			url= thisObj.data("ebulletin-url");
			var output = jQuery('<iframe src="' + url + '"></iframe>');
			jQuery('.ebulletin-wrapper > iframe').remove();
			jQuery('.ebulletin-wrapper').css("height", "75px").find('div.ebulletin-link').css("display", "none");
			thisObj.css("height", "800px");
			thisObj.append(output);
			thisObj.find('div.ebulletin-link').css("display", "inline-block");
		});
		ebulletinsContainer.append(output);
	}
}

campaigns.each(generateCard(ebulletinsContainer));
archiveContainer.remove();
</script>
