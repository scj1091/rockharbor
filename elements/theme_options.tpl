<?php
global $theme;
if (!isset($_REQUEST['updated'])) {
	$_REQUEST['updated'] = false;
}

?>
<div class="wrap">
	<?php
	screen_icon();
	echo '<h2>Theme Options</h2>';
	?>

	<?php if ($_REQUEST['updated'] !== false): ?>
	<div class="updated fade"><p><strong>Options saved</strong></p></div>
	<?php endif; ?>

	<form method="post" action="options.php">
		<?php 
		settings_fields($theme->info('slug').'_options');
		$theme->Html->inputPrefix = $theme->info('slug').'_options';
		$theme->Html->data($theme->options());
		?>
		<table class="form-table">
			<tr valign="top">
				<?php 
					echo $theme->Html->input('header_image', array(
						'before' => '<th>',
						'label' => 'Header Image',
						'between' => '</th><td>',
						'after' => '<br /><small>(will be used for video poster if a video is defined)</small></td>'
					)); 
				?>
			</tr>
			<tr valign="top">
				<?php 
					echo $theme->Html->input('header_video', array(
						'before' => '<th>',
						'label' => 'Header Video',
						'between' => '</th><td>',
						'after' => '</td>'
					)); 
				?>
			</tr>
			<tr valign="top">
				<?php 
					echo $theme->Html->input('header_ad_space', array(
						'type' => 'textarea',
						'rows' => 10,
						'cols' => 60,
						'before' => '<th>',
						'label' => 'Header Ad Space',
						'between' => '</th><td>',
						'after' => '<br /></td>'
					)); 
				?>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Save" />
		</p>
	</form>
</div>
<script>
	jQuery(".thickbox").bind("click", function (e) {
	tmpFocus = undefined;
	isTinyMCE = false;
});
var isTinyMCE;
var tmpFocus;
function focusTextArea(id) {
	jQuery(document).ready(function() {
		if ( typeof tinyMCE != "undefined" ) {
			var elm = tinyMCE.get(id);
		}
		if ( ! elm || elm.isHidden() ) {
			elm = document.getElementById(id);
			isTinyMCE = false;
		}else isTinyMCE = true;
		tmpFocus = elm
		elm.focus();
		if (elm.createTextRange) {
			var range = elm.createTextRange();
			range.move("character", elm.value.length);
			range.select();
		} else if (elm.setSelectionRange) {
			elm.setSelectionRange(elm.value.length, elm.value.length);
		}
	});
}
function thickbox(link) {
	var t = link.title || link.name || null;
	var a = link.href || link.alt;
	var g = link.rel || false;
	tb_show(t,a,g);
	link.blur();
	return false;
}
</script>