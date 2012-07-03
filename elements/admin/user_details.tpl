<?php
$theme->Html->inputPrefix = 'usermeta';
$theme->Html->data($data);
?>
<h3>ROCKHARBOR CMS Permissions</h3>
<table class="form-table">
	<tbody>
		<tr>
			<th>
				<?php 
				echo $theme->Html->tag('label', 'Can only access own posts', array(
					'for' => 'usermetashowonlyownedposts'
				));
				?>
			</th>
			<td>
				<?php
				echo $theme->Html->input('show_only_owned_posts', array(
					'label' => false,
					'type' => 'checkbox'
				));
				?>
			</td>
		</tr>
	</tbody>
</table>