<div class="core-events">
<?php
if (!empty($events)):
	$lastDate = null;
	for ($i=0; $i<5; $i++): if (!isset($events[$i])) { continue; } $event = $events[$i]; ?>
		<div class="core-event">
			<div class="core-times">
				<?php
				$start = date('Y-m-d', strtotime($event['start']));
				if ($start !== $lastDate) {
					$theme->set('date', $event['start']);
					echo $theme->render('posted_date');
				}
				$lastDate = $start;
				?>
			</div>
			<a target="_blank" href="<?php echo $event['url']; ?>"><?php echo $event['title']; ?></a>
		</div>
	<?php endfor; ?>
<?php else: ?>
<p>There are no upcoming events.</p>
<?php endif; ?>
</div>