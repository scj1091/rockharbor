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
			url: 'http://core2.local/dates/calendar<?php echo !empty($id) ? "/Ministry:$id" : null; ?>/full.json?start='+Math.round(start.getTime()/1000)+'&end='+Math.round(end.getTime()/1000),
			dataType: 'jsonp',
			success: callback
		});
	}
});</script>