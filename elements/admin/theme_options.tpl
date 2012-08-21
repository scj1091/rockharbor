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
				<td colspan="2"><h2>Social Settings</h2><p>All things social related to <?php echo $theme->info('short_name'); ?>.</p></td>
			</tr>
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
			<tr valign="top">
				<?php 
					echo $theme->Html->input('core_id', array(
						'before' => '<th>',
						'label' => 'CORE ID',
						'between' => '</th><td>',
						'after' => '<br /><small>(the CORE campus ID)</small></td>'
					)); 
				?>
			</tr>
			<tr valign="top">
				<?php 
					echo $theme->Html->input('mailchimp_id', array(
						'before' => '<th>',
						'label' => 'MailChimp ID',
						'between' => '</th><td>',
						'after' => '<br /><small>(the group id under the ebulletins group in MailChimp)</small></td>'
					)); 
				?>
			</tr>
			<tr valign="top">
				<td colspan="2"><h2>Notifications</h2><p>Forms and notifications that require an email address.</p></td>
			</tr>
			<tr valign="top">
				<?php 
					echo $theme->Html->input('story_email', array(
						'before' => '<th>',
						'label' => 'Share Your Story Email',
						'between' => '</th><td>',
						'after' => '<br /><small>(a comma-delimited list of email addresses to send stories to)</small></td>'
					)); 
				?>
			</tr>
			<tr valign="top">
				<?php 
					echo $theme->Html->input('prayer_request_email', array(
						'before' => '<th>',
						'label' => 'Prayer Request Email',
						'between' => '</th><td>',
						'after' => '<br /><small>(a comma-delimited list of email addresses to send prayer requests to)</small></td>'
					)); 
				?>
			</tr>
			<tr valign="top">
				<td colspan="2"><h2>Admin</h2><p>Don't touch this stuff unless you know what you're doing.</p></td>
			</tr>
			<tr valign="top">
				<?php 
					echo $theme->Html->input('feedburner_main', array(
						'before' => '<th>',
						'label' => 'Main Feedburner Link ID',
						'between' => '</th><td>',
						'after' => '<br /><small>(http://feeds.feedburner.com/<strong>feedid</strong>)</small></td>'
					)); 
				?>
			</tr>
			<tr valign="top">
				<?php 
					echo $theme->Html->input('s3_streamer', array(
						'before' => '<th>',
						'label' => 'Amazon S3 Streaming Distibution ID',
						'between' => '</th><td>',
						'after' => '<br /><small>(<strong>&lt;ID&gt;</strong>.cloudfront.net)</small></td>'
					)); 
				?>
			</tr>
			<tr valign="top">
				<?php 
					echo $theme->Html->input('media_sync_key', array(
						'before' => '<th>',
						'label' => 'Media Syncing Key',
						'between' => '</th><td>',
						'after' => '<br /><small>(used to authorize running sync.php)</small></td>'
					)); 
				?>
			</tr>
			<tr>
				<th>
					<label for="ebulletin_archive_page_id" class="description">Ebulletin Archive Page</label>
				</th>
				<td>
				<?php 
					wp_dropdown_pages(array(
						'name' => $theme->Html->inputPrefix.'[ebulletin_archive_page_id]', 
						'show_option_none' => __('&mdash; Select &mdash;'), 
						'option_none_value' => '0', 
						'selected' => $theme->options('ebulletin_archive_page_id'),
						'id' => 'ebulletin_archive_page_id'
					));
				?>
				</td>
			</tr>
			<tr>
				<th>
					<label for="calendar_page_id" class="description">Calendar Page</label>
				</th>
				<td>
				<?php 
					wp_dropdown_pages(array(
						'name' => $theme->Html->inputPrefix.'[calendar_page_id]', 
						'show_option_none' => __('&mdash; Select &mdash;'), 
						'option_none_value' => '0', 
						'selected' => $theme->options('calendar_page_id'),
						'id' => 'calendar_page_id'
					));
				?>
				</td>
			</tr>
			<tr valign="top">
				<?php 
					echo $theme->Html->input('core_calendar_id', array(
						'before' => '<th>',
						'label' => 'CORE Calendar Campus ID',
						'between' => '</th><td>',
						'after' => '<br /><small>(the campus ID(s) to pull calendar events from)</small></td>'
					)); 
				?>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Save" />
		</p>
	</form>
</div>