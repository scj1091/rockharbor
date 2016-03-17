<?php
wp_dequeue_script('leaflet');
wp_dequeue_style('leaflet');
$min = WP_DEBUG ? '' : '.min';
wp_register_style('individual-events', trailingslashit(get_template_directory_uri()) . "css/individual-events{$min}.css");
wp_enqueue_style('individual-events');
 ?>
<div class="rh-ccbpress-single-event">
	<div class="rh-ccbpress-single-event-header">
		<div id="rh-ccbpress-clock"></div><div class="rh-ccbpress-event-recurrence-description clearfix"><?php echo ccbpress_event_recurrence_desc( $event ); ?></div>
	</div>
	<div class="rh-ccbpress-event-description"><?php echo wpautop( $event->description, true ); ?></div>
    <div class="rh-ccbpress-event-organizer"><span>Event organizer: <?php echo $event->organizer; ?></span>&nbsp;
        <a href="tel:+1<?php echo preg_replace("/[^0-9]/", "", $event->phone);?>"><?php echo $event->phone; ?></a></div>
	<?php
	global $post;
	$pageUrl = get_permalink($post->ID);
	$eventID = get_query_var('ccbpress_event_id');
	$pageUrl = rawurlencode($pageUrl . '?ccbpress_event_id=' . $eventID);
	?>
	<div class="rh-ccbpress-rsvp">
		<a href="<?php echo 'https://rockharbor.ccbchurch.com/w_calendar.php#events/' . $event['id']; ?>" class="rh-button">RSVP</a>
	</div>
	<div class="rh-ccbpress-share">
		<h3>SHARE</h3>&nbsp;<a class="share facebook" href="<?php echo "http://www.facebook.com/sharer.php?u=$pageUrl&t=$event->name"?>"><span class="icon icon-facebook"></span></a>
		<a class="share twitter" href="<?php echo "http://www.twitter.com/home?status=Check out $event->name at $pageUrl"?>"><span class="icon icon-twitter"></span></a>
		<script type="text/javascript">
			jQuery('.share').click(function() {
				window.open(jQuery(this).attr('href'), 'share', 'width=500,height=400');
				return false;
			});
		</script>
	</div>

	<?php
	/**
	 * Check if there are any registration forms attached to the event
	 */
	if ( $event->registration->forms->registration_form ) :  ?>
		<?php
		/**
		 * Start the output buffer
		 */
		ob_start(); ?>
		<div class="rh-ccbpress-event-registration-forms rh-event-info-box">
			<div class="rh-ccbpress-event-registration-forms-header rh-event-info-box-header"><?php _e( 'Registration Form(s):', 'ccbpress' ); ?></div>
			<div class="rh-ccbpress-event-registration-forms-content rh-event-info-box-content">
				<ul>
					<?php
					/**
					 * Lets start our registration form count at 0
					 */
					$registration_form_count = 0; ?>
					<?php
					/**
					 * Loop through each registration form
					 */
					foreach( $event->registration->forms->registration_form as $registration_form ) : ?>
						<?php
						/**
						 * Only show it if the form is still active
						 */
						if ( ccbpress_is_form_active( $registration_form ) ) : ?>
							<li><a href="<?php echo $registration_form->url; ?>" class="<?php echo ccbpress_lightbox_class(); ?>"><?php echo $registration_form->name; ?></a></li>
							<?php
							/**
							 * Increase our registration form count by 1
							 */
							$registration_form_count++; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php
		/**
		 * Clean the output buffer and save it to a variable
		 */
		$registration_forms = ob_get_clean(); ?>
		<?php
		/**
		 * Only display the registration form section if we have at least 1 registration form
		 */
		if ( $registration_form_count > 0 ) : ?>
			<?php echo $registration_forms; ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	/**
	 * Check if there are any exceptions for this event
	 */
	if ( $event->exceptions->exception ) : ?>
		<?php
		/**
		 * Start the output buffer
		 */
		ob_start(); ?>
		<div class="rh-ccbpress-event-exceptions rh-event-info-box">
			<div class="rh-ccbpress-event-exceptions-header rh-event-info-box-header"><?php echo "Doesn't occur on:"; ?></div>
			<div class="rh-ccbpress-event-exceptions-content">
				<ul>
					<?php
					/**
					 * Lets start our future exceptions count at 0
					 */
					$future_exceptions = 0; ?>
					<?php
					/**
					 * Loop through each exception
					 */
					foreach ( $event->exceptions->exception->date as $exception_date ) : ?>
						<?php
						/**
						 * Check if the exception is in the future
						 */
						if ( ccbpress_is_future_date( $exception_date ) ) :
							$objDate = DateTime::createFromFormat('Y-m-d', $exception_date); ?>
							<li><?php echo date('l F j, Y', $objDate->getTimestamp()); ?></li>
							<?php
							/**
							 * Increase our future exception count by 1
							 */
							$future_exceptions++; ?>
						<?php endif; ?>
					<?php endforeach;?>
				</ul>
			</div>
		</div>
		<?php
		/**
		 * Clean the output buffer and save it to a variable
		 */
		$exceptions = ob_get_clean(); ?>
		<?php
		/**
		 * Only display the exceptions section if we have at least 1 future exception
		 */
		if ( $future_exceptions > 0 ) : ?>
			<?php echo $exceptions; ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	/**
	 * Check if there are is a location for this event
	 */
	if ( $event->location->name ) :
		$markerTitle = ($event->location->name != '') ? $event->location->name : trim($event->location->line_1);
		$addressString = '<div style=\"line-height: 1.35; overflow: hidden; white-space: nowrap\">' . @$event->location->name . '</br>' . trim($event->location->line_1) .
			'<br/>' . trim($event->location->line_2) . '</div>'; ?>
		<div class="rh-ccbpress-event-location rh-event-info-box">
			<div class="rh-ccbpress-event-location-header rh-event-info-box-header"><?php _e('Location:', 'ccbpress'); ?></div>
			<div id="google-map" data-address="<?php echo $event->location->line_1 . $event->location->line_2; ?>"></div>
			<div class="rh-ccbpress-event-location-nomap"><?php echo ccbpress_map_label_from_event( $event->location ); ?></div>
		</div>
		<script type="text/javascript">
		function initMap() {
			var geocoder = new google.maps.Geocoder();
			var address = jQuery('#google-map').data('address');
			geocoder.geocode({'address': address}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK) {
					var mapDiv = jQuery('#google-map').width('100%').height('300px');
					var nomapDiv = jQuery('.rh-ccbpress-event-location-nomap').css('display', 'none');
					var map = new google.maps.Map(mapDiv.get(0), {
						zoom: 13,
						center: {lat:33.5, lng:-118}
					});
					map.setCenter(results[0].geometry.location);
					var marker = new google.maps.Marker({
						map: map,
						position: results[0].geometry.location,
						title: "<?php echo $markerTitle ?>"
					});
					var infowindow = new google.maps.InfoWindow({
						content: "<?php echo $addressString; ?>"
					});
					marker.addListener('click', function() {
						infowindow.open(map, marker);
					});
				}
			});
		}
		</script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB3HEVaDRiRa_VcrpVYpfrwlYcz2MRccBc&signed_in=false&callback=initMap" async defer></script>
	<?php endif; ?>
</div>
