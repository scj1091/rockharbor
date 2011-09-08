<?php
include 'shortcode-inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Video Shortcode</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript">
	function init() {
		tinyMCEPopup.resizeToInnerSize();
	}

/**
 * Generates shortcode and inserts it
 */
	function insertShortcode() {
		var video = document.getElementById('vidurl').value;

		if (video == '') {
			alert('Please type in a full video url');
			return false;
		}

		var tagtext = '[videoplayer src="'+video+'"]';
		
		if (window.tinyMCE) {
			window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
			//Peforms a clean up of the current editor HTML. 
			//tinyMCEPopup.editor.execCommand('mceCleanup');
			//Repaints the editor. Sometimes the browser has graphic glitches. 
			tinyMCEPopup.editor.execCommand('mceRepaint');
			tinyMCEPopup.close();
		}
		return;
	}
	</script>
	<base target="_self" />
</head>
<body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';document.getElementById('vidurl').focus();" style="display: none">
	<form name="shortcode" action="#">
		<div class="tabs">
			<ul>
				<li id="details_tab" class="current"><span><a href="javascript:mcTabs.displayTab('details_tab','details_panel');" onmousedown="return false;">Details</a></span></li>
			</ul>
		</div>

		<div class="panel_wrapper">
			<div id="details_panel" class="panel current">
				<br />
				<table border="0" cellpadding="4" cellspacing="0">
					<tr>
						<td nowrap="nowrap">
							<label for="vidurl">Video URL:</label>
						</td>
						<td>
							<input type="text" id="vidurl" name="vidurl" style="width: 190px" />
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="mceActionPanel">
			<div style="float: left">
				<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
			</div>

			<div style="float: right">
				<input type="submit" id="insert" name="insert" value="Insert" onclick="insertShortcode();" />
			</div>
		</div>
	</form>
</body>
</html>