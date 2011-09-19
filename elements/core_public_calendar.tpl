<div class="core-events">
<?php
if (!isset($id)) {
	$id = 0;
}

$response = wp_remote_get("https://core.rockharbor.org/homes/publicCalendar/$id/json", array('sslverify' => false));
if (is_wp_error($response)) {
	$response = array(
		'body' => json_encode(array('results' => array()))
	);
}
$items = json_decode($response['body'], true);

if (!empty($items)):
	for ($i=0; $i<5; $i++): if (!isset($items[$i])) { continue; } $item = $items[$i]; ?>
		<div class="core-event">
			<time datetime="<?php echo date('Y-m-d', strtotime($item[0]['event_date'])); ?>">
				<span class="month"><?php echo date('M', strtotime($item[0]['event_date'])); ?></span>
				<span class="day"><?php echo date('j', strtotime($item[0]['event_date'])); ?></span>
				<span class="year"><?php echo date('Y', strtotime($item[0]['event_date'])); ?></span>
			</time>
			<a target="_blank" href="https://core.rockharbor.org/events/<?php echo $item['Events']['event_id']; ?>"><?php echo $item['Events']['event_name']; ?></a>
		</div>
	<?php endfor; ?>
<?php else: ?>
<p>There are no upcoming events.</p>
<?php endif; ?>
</div>


