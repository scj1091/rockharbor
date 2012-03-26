<div class="core-events">
<?php
if (!empty($events)):
	for ($i=0; $i<5; $i++): if (!isset($events[$i])) { continue; } $event = $events[$i]; ?>
		<div class="core-event">
			<div class="core-times">
				<?php
				$theme->set('date', $event['start']);
				echo $theme->render('posted_date');
				if ($event['end'] != $event['start']) {
					$theme->set('date', $event['end']);
					echo ' - '.$theme->render('posted_date');
				}
				?>
			</div>
			<a target="_blank" href="<?php echo $event['url']; ?>"><?php echo $event['title']; ?></a>
		</div>
	<?php endfor; ?>
<?php else: ?>
<p>There are no upcoming events.</p>
<?php endif; ?>
</div>


