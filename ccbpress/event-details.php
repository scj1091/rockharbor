<?php
//die(print_r($event));
wp_dequeue_script('leaflet');
wp_dequeue_style('leaflet');
$min = WP_DEBUG ? '' : '.min';
wp_register_style('event-details', trailingslashit(get_template_directory_uri()) . "css/event-details{$min}.css", array('dashicons'));
wp_enqueue_style('event-details');

$splitColumns = (!empty($event->location_name) || $template->has_exceptions($event) || !empty($event->registration_forms));

/**
 *  Get registration forms
 *  If there's more than one active one, use the first
 *  If there isn't a registration form, the RSVP link points to the event page on CCB
 */
$registration_link = false;
//die(print_r($event->registration_forms));
if ($event->registration_forms->registration_form) {
    //echo "in reg form if";
    foreach ($event->registration_forms->registration_form as $registration_form) {
        echo print_r($registration_form);
        if ($template->is_form_active($registration_form)) {
            echo "event form active";
            $registration_link = $registration_form->url;
            break;
        }
    }
}

 ?>
<div class="rh-ccbpress-event-details <?php if ($splitColumns) { echo "rh-ccbpress-left-column"; } ?>">
    <?php if ((get_option('ccbpress_individual_event_show_image', '1') == '1') && $template->has_event_image( $event ) ): ?>
        <div class="rh-ccbpress-event-image"><img src="<?php echo $event->image; ?>" /></div>
    <?php
    endif;
    if ( (get_option('ccbpress_individual_event_show_organizer') == '1') && $event->organizer != ''): ?>
    <div class="rh-ccbpress-event-organizer"><span>Event organizer: <?php echo $event->organizer; ?></span>&nbsp;
        <?php if ( $event->phone != '' ): ?>
        <a href="tel:+1<?php echo preg_replace("/[^0-9]/", "", $event->phone);?>"><?php echo $event->phone; ?></a>
        <?php endif; ?>
    </div>
    <?php endif;
    if (empty($event->description)) : ?>
    <div class="rh-ccbpress-event-description"><p>No description has been provided for this event</p></div>
    <?php else: ?>
	<div class="rh-ccbpress-event-description"><?php echo wpautop( $event->description, true ); ?></div>
    <?php
    endif;
	global $post;
	$pageUrl = get_permalink($post->ID);
	$eventID = get_query_var('ccbpress_event_id');
	$pageUrl = rawurlencode($pageUrl . '?ccbpress_event_id=' . $eventID);
	?>
</div>
<?php if ($splitColumns): ?>
<div class="rh-ccbpress-right-column">
    <div class="rh-ccbpress-actions clearfix">
        <div class="rh-ccbpress-rsvp">
    		<a href="<?php
            if ($registration_link) {
                echo $registration_link;
            } else {
                echo 'https://rockharbor.ccbchurch.com/w_calendar.php#events/' . $eventID;
            }?>" class="rh-button">RSVP</a>
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
    </div>
	<?php
	/**
	 * Check if there are is a location for this event
	 */
	if ( $event->location_name ) :
        $cleanLocationName = str_replace(array("\r\n", "\r", "\n"), "<br/>", @$event->location_name);
        $cleanAddress1 = str_replace(array("\r\n", "\r", "\n"), "<br/>", $event->location_line_1);
        $cleanAddress2 = str_replace(array("\r\n", "\r", "\n"), "<br/>", $event->location_line_2);
		$markerTitle = ($cleanLocationName != '') ? $cleanLocationName : trim($cleanAddress1);
		$addressString = '<div style=\"line-height: 1.35; overflow: hidden; white-space: nowrap\">' . $cleanLocationName . '</br>' . trim($cleanAddress1) .
			'<br/>' . trim($cleanAddress2) . '</div>'; ?>
		<div class="rh-ccbpress-event-location rh-event-info-box">
			<div class="rh-event-info-box-header clearfix"><div class="rh-ccbpress-event-location-header rh-event-info-box-icon"><span class="dashicons dashicons-location-alt"></span></div><div class="rh-ccbpress-event-location-header rh-event-info-box-header-text">Where</div></div>
			<div id="google-map" data-address="<?php echo $event->location_line_1 . $event->location_line_2; ?>"></div>
			<div class="rh-ccbpress-event-location-nomap"><?php echo $template->map_label( $event->location_name, $event->location_line_1, $event->location_line_2 ); ?></div>
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
						title: "<?php echo str_replace(array("\r\n", "\n", "\r"), "<br/>", $markerTitle) ?>"
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
        // Fix titles without hacking plugin
        jQuery(document).ready(function() {
            var eventName = "<?php echo $event->name; ?>";
            jQuery('.breadcrumbs').append('&nbsp;/&nbsp;<span class="crumb"><?php echo str_replace("'", "\'", $event->name); ?></span>');
            jQuery('#content-title h1').html(eventName);
        });
		</script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB3HEVaDRiRa_VcrpVYpfrwlYcz2MRccBc&callback=initMap" async defer></script>
	<?php
    endif;

    ?>
    <div class="rh-event-info-box">
        <div class="rh-event-info-box-header clearfix"><div class="rh-event-info-box-icon"><div id="rh-ccbpress-clock"></div></div><div class="rh-event-info-box-header-text">When</div></div>
        <div class="rh-ccbpress-event-recurrence-description"><?php echo $template->recurrence_desc( $event ) ; ?></div>
    </div>
    <?php

	/**
	 * Check if there are any exceptions for this event
	 */
	if ( $template->has_exceptions( $event ) ) {
		/**
		 * Start the output buffer
		 */
		ob_start(); ?>
		<div class="rh-ccbpress-event-exceptions rh-event-info-box">
			<div class="rh-event-info-box-header clearfix"><div class="rh-event-info-box-icon"><span class="dashicons dashicons-calendar-alt"><span></div><div class="rh-ccbpress-event-exceptions-header rh-event-info-box-header-text"><?php echo "Doesn't occur on"; ?></div></div>
			<div class="rh-ccbpress-event-exceptions-content clearfix">
				<?php
				/**
				 * Lets start our future exceptions count at 0
				 */
				$future_exceptions = 0; ?>
				<?php
				/**
				 * Loop through each exception
				 */
				foreach ( $event->exceptions->exception as $exception ) : ?>
					<?php
					/**
					 * Check if the exception is in the future
					 */
					if ( $template->is_future_date( $exception ) ) :
						$objDate = DateTime::createFromFormat('Y-m-d', $exception->date); ?>
						<div class="clearfix"><div class="rh-ccbpress-event-date-box">
                            <div class="rh-ccbpress-event-date-box-month"><?php echo date('M', $objDate->getTimestamp()); ?></div>
                            <div class="rh-ccbpress-event-date-box-date"><?php echo date('j', $objDate->getTimestamp()); ?></div>
                        </div><div class="rh-ccbpress-event-date-box-text"><?php echo date('l F j, Y', $objDate->getTimestamp()); ?></div></div>
						<?php
						/**
						 * Increase our future exception count by 1
						 */
						$future_exceptions++; ?>
					<?php endif; ?>
				<?php endforeach;?>
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
		if ( $future_exceptions > 0 ) {
			echo $exceptions;
		}
	}

	/**
	 * Check if there are any registration forms attached to the event
	 */
	if ( $event->registration_forms ) :  ?>
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
					foreach( $event->registration_forms as $registration_form ) : ?>
						<?php
						/**
						 * Only show it if the form is still active
						 */
						if ( $template->is_form_active( $registration_form ) ) : ?>
							<li><a href="<?php echo $registration_form->url; ?>" class="<?php echo $template->lightbox_class(); ?>"><?php echo $registration_form->name; ?></a></li>
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
</div>
<?php endif; ?>
