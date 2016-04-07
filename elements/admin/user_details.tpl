<?php
$theme->Html->inputPrefix = 'usermeta';
$theme->Html->data($data);
?>
<h2>Permissions</h2>
<table class="form-table">
	<tbody>
		<tr>
			<th>User can</th>
			<td>
				<?php
				echo $theme->Html->input('show_only_owned_posts', array(
					'label' => 'Only access his/her posts',
					'type' => 'checkbox'
				));
				?>
			</td>
		</tr>
	</tbody>
</table>
