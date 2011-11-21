<?php
$feed = array();
foreach ($events as $event) {
	$feed[] = array(
		'title' => $event['Event']['name'],
		'start' => $event['Date'][0]['start_date'],
		'url' => 'https://core.rockharbor.org/events/'.$event['Event']['id']
	);
}
?>
<div id="calendar"></div>
<link rel="stylesheet" media="all" href="<?php echo $theme->info('base_url'); ?>/css/calendar.css" />
<script src="<?php echo $theme->info('base_url'); ?>/js/fullcalendar.js"></script>
<script>jQuery('#calendar').fullCalendar({
	header: {
		left: '',
		center: 'prev,title,next',
		right: ''
	},
	buttonText: {
		prev: '&lt;',
		next: '&gt;'
	},
	events: <?php echo json_encode($feed); ?>
});</script>