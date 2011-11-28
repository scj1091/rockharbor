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
	events: function(start, end, callback) {
		jQuery.ajax({
			url: 'https://core.rockharbor.org/homes/publicCalendar/<?php echo $id; ?>/'+Math.round(start.getTime()/1000)+'/'+Math.round(end.getTime()/1000)+'/json',
			dataType: 'jsonp',
			success: function(data) {
				var events = [];
				for (var e in data) {
					events.push({
						title: data[e].Events.event_name,
						start: data[e].EventDates.start_date,
						end: data[e].EventDates.end_date,
						allDay: false,
						url: 'https://core.rockharbor.org/events/'+data[e].Events.event_id
					});
				}
				callback(events);
			}
		});
	}
});</script>