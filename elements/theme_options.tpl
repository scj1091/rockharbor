<?php
if (!isset($_REQUEST['settings-updated'])) {
	$_REQUEST['settings-updated'] = false;
}

?>
<div class="wrap">
	<?php
	screen_icon();
	echo '<h2>Theme Options</h2>';
	?>

	<?php if ($_REQUEST['settings-updated'] !== false): ?>
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
					echo $theme->Html->input('twitter_user', array(
						'before' => '<th>',
						'label' => 'Twitter User',
						'between' => '</th><td>',
						'after' => '<br /><small>(twitter username, without the @ symbol)</small></td>'
					)); 
				?>
			</tr>
			<tr valign="top">
				<?php 
					echo $theme->Html->input('twitter_search', array(
						'before' => '<th>',
						'label' => 'Twitter Search',
						'between' => '</th><td>',
						'after' => '<br /><small>(search operators: <a target="_blank" href="https://dev.twitter.com/docs/using-search">https://dev.twitter.com/docs/using-search</a>)</small></td>'
					)); 
				?>
			</tr>
			<tr valign="top">
				<?php 
					echo $theme->Html->input('facebook_user', array(
						'before' => '<th>',
						'label' => 'Facebook Page User',
						'between' => '</th><td>',
						'after' => '<br /><small>(the username in www.facebook.com/<strong>&lt;username&gt;</strong>)</small></td>'
					)); 
				?>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Save" />
		</p>
	</form>
</div>