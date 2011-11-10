<div class="core-events">
<?php
if (!empty($events)):
	for ($i=0; $i<5; $i++): if (!isset($events[$i])) { continue; } $event = $events[$i]; ?>
		<div class="core-event">
			<?php
			$theme->set('date', $event['Date'][0]['start_date']);
			echo $theme->render('posted_date');
			?>
			<a target="_blank" href="https://core.rockharbor.org/<?php echo $event['Event']['type']; ?>s/<?php echo $event['Event']['id']; ?>"><?php echo $event['Event']['name']; ?></a>
		</div>
	<?php endfor; ?>
<?php else: ?>
<p>There are no upcoming events.</p>
<?php endif; ?>
</div>


