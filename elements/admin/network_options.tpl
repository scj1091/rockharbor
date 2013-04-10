<?php
if (!isset($_REQUEST['settings-updated'])) {
	$_REQUEST['settings-updated'] = false;
}
?>
<div class="wrap">
	<?php
	echo '<h2>Network Options</h2>';
	?>

	<?php if ($_REQUEST['settings-updated'] !== false): ?>
	<div class="updated fade"><p><strong>Options saved</strong></p></div>
	<?php endif; ?>

	<form method="post" action="/wp-admin/options.php">
		<?php
		settings_fields('rockharbor_network_options');
		$theme->Html->inputPrefix = 'rockharbor_network_options';
		$theme->Html->data($theme->networkOptions());
		?>
		<table class="form-table">
			<tr valign="top">
				<td colspan="2"><h2>Cross posting</h2><p>Choose where campuses are allowed to cross post.</p></td>
			</tr>
			<?php
			$blogs = $theme->getBlogs();
			foreach ($blogs as $blog):
				$theme->Html->inputPrefix = 'rockharbor_network_options[cross_post_whitelist_'.$blog['blog_id'].']';
				$theme->Html->data($theme->networkOptions('cross_post_whitelist_'.$blog['blog_id']));
				$checkboxes = array();
			?>
			<tr valign="top">
				<?php
				foreach ($blogs as $crossPostBlog) {
					if ($blog['blog_id'] == $crossPostBlog['blog_id']) {
						continue;
					}
					$checkboxes[] = $theme->Html->input($crossPostBlog['blog_id'], array(
						'type' => 'checkbox',
						'label' => $crossPostBlog['domain'],
						'value' => 1
					));
				}
				?>
				<th><label><?php echo $blog['domain']; ?></label></th>
				<td><?php echo implode('', $checkboxes); ?></td>
			</tr>
			<?php
			endforeach;
			$theme->Html->inputPrefix = 'rockharbor_network_options';
			?>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Save" />
		</p>
	</form>
</div>