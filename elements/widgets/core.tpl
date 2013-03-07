<div class="core-events clearfix">
<?php
if (!empty($events)):
	$lastDate = null;
	for ($i=0; $i<5; $i++): if (!isset($events[$i])) { continue; } $event = $events[$i]; ?>
		<?php
		$start = date('Y-m-d', strtotime($event['start']));
		if ($start !== $lastDate) {
			$theme->set('date', $event['start']);
			echo $theme->render('posted_date');
		}
		$lastDate = $start;
		?>
		<a target="_blank" href="<?php echo $event['url']; ?>"><?php echo $event['title']; ?></a>
	<?php endfor; ?>
<?php else: ?>
<p>There are no upcoming events.</p>
<?php endif; ?>
</div>