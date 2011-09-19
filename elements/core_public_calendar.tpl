<div class="core-events">
<?php
if (!empty($events)):
	for ($i=0; $i<5; $i++): if (!isset($events[$i])) { continue; } $event = $events[$i]; ?>
		<div class="core-event">
			<time datetime="<?php echo date('Y-m-d', strtotime($event['Date'][0]['start_date'])); ?>">
				<span class="month"><?php echo date('M', strtotime($event['Date'][0]['start_date'])); ?></span>
				<span class="day"><?php echo date('j', strtotime($event['Date'][0]['start_date'])); ?></span>
				<span class="year"><?php echo date('Y', strtotime($event['Date'][0]['start_date'])); ?></span>
			</time>
			<a target="_blank" href="https://core.rockharbor.org/events/<?php echo $event['Event']['id']; ?>"><?php echo $event['Event']['name']; ?></a>
		</div>
	<?php endfor; ?>
<?php else: ?>
<p>There are no upcoming events.</p>
<?php endif; ?>
</div>


