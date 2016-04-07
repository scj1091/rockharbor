<?php
/**
 * [ccbpress_events] Shortcode Output
 *
 * @package    CCBPress
 * @since      1.0
 */

 /**
 * Filter to display the calendar of events.
 *
 * @since 1.0.0
 *
 * @param	object	$events	An object containing all of the events.
 *
 * @return	string	HTML content to display the event calendar.
 */
function rh_ccbpress_event_list_display( $events ) {

	global $ccbpress_ccb;

	$campus_id = get_option( 'ccbpress_event_calendar_campus', 'ccbpress_all' );
	if ( $override_campus_id = get_query_var( 'ccbpress_campus_id' ) ) {
		$campus_id = $override_campus_id;
	}

	if ( $campus_id != 'ccbpress_all' ) {
		$group_profiles = $ccbpress_ccb->group_profiles( array(
			'campus_id'			=> $campus_id,
			'modified_since'	=> (string)date( 'Y-m-d', strtotime('-6 months') )
		) );
		$cw_group_profiles = $ccbpress_ccb->group_profiles(array(
			'campus_id' => 1,
			'modified_since' => (string) date('Y-m-d', strtotime('-6 months'))
		));
	}

	$campus_hide = get_option( 'ccbpress_event_calendar_campus_hide', '0' );

	$ccbpress_campuses = ccbpress_get_campus_list( true );
	$ccbpress_campuses = ( ! is_array( $ccbpress_campuses ) ) ? $ccbpress_campuses = array() : $ccbpress_campuses;
    $ccbpress_campuses = array( 'ccbpress_all' => __( 'All Campuses' ) ) + $ccbpress_campuses;

	// Determine the current calendar year
	if ( ! ( $calendar_year = get_query_var( 'ccbpress_event_year' ) ) ) {
		$calendar_year = date( 'Y' );
	}

	// Determine the current calendar month
	if ( ! ( $calendar_month = get_query_var( 'ccbpress_event_month' ) ) ) {
		$calendar_month = date( 'n' );
	}

	// Calculate the previous month and year
	$prev_month = $calendar_month - 1;
	$prev_year = $calendar_year;

	if ( $prev_month == 0 ) {
		$prev_month = 12;
		$prev_year = $calendar_year - 1;
	}

	// Calculate the next month and year
	$next_month = $calendar_month + 1;
	$next_year = $calendar_year;

	if ( $next_month == 13 ) {
		$next_month = 1;
		$next_year = $calendar_year + 1;
	}

	$permalink = get_permalink();
	if ( get_query_var( 'ccbpress_event_year' ) ) {
		$permalink = add_query_arg( 'ccbpress_event_year', $calendar_year, $permalink );
	}
	if ( get_query_var( 'ccbpress_event_month' ) ) {
		$permalink = add_query_arg( 'ccbpress_event_month', $calendar_month, $permalink );
	}

	$ccbpress_next_permalink = ccbpress_get_events_url( $next_year, $next_month );
	$ccbpress_prev_permalink = ccbpress_get_events_url( $prev_year, $prev_month );

	if ( get_query_var( 'ccbpress_campus_id' ) ) {
		$ccbpress_next_permalink = add_query_arg( 'ccbpress_campus_id', $campus_id, $ccbpress_next_permalink );
		$ccbpress_prev_permalink = add_query_arg( 'ccbpress_campus_id', $campus_id, $ccbpress_prev_permalink );
	}

	$campus_hide_class = '';
	if ( $campus_hide != '1' ) {
		$campus_hide_class = ' ccbpress-campus-visible';
	}

	// Save the output to a variable
	$html = '<div class="ccbpress-event-calendar' . $campus_hide_class . '">';
	$html .= '	<div class="ccbpress-event-calendar-header">';
	$html .= '		<div class="ccbpress-event-calendar-prev-month">';
	$html .= '			<a href="' . $ccbpress_prev_permalink . '"><div class="dashicons dashicons-arrow-left-alt2"></div></a>';
	$html .= '		</div>';
	$html .= '		<div class="ccbpress-event-calendar-next-month">';
	$html .= '			<a href="' . $ccbpress_next_permalink . '"><div class="dashicons dashicons-arrow-right-alt2"></div></a>';
	$html .= '		</div>';
	if ( $campus_hide != '1' ) {
		$html .= '		<div class="ccbpress-event-calendar-campus">';
		$html .= '			<select>';
		foreach( $ccbpress_campuses as $c_id => $c_name ) {
			$html .= '<option value="' . $c_id . '" ' . selected( $c_id, $campus_id, false ) . ' data-url="' . esc_attr( add_query_arg('ccbpress_campus_id', $c_id, $permalink ) ) . '">' . $c_name . '</option>';
		}
		$html .= '			</select>';
		$html .= '		</div>';
	}
	$html .= '		<div class="ccbpress-event-calendar-month">';
	$html .= 			apply_filters( 'ccbpress_event_calendar_month_text', date( 'F Y', mktime( null, null, null, $calendar_month, 1, $calendar_year ) ) );
	$html .= '		</div>';
	$html .= '	</div>';
	$html .= '	<table class="ccbpress-event-calendar-table">';
	$html .= '		<tr>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . __('Sunday', 'ccbpress') . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . __('Sun', 'ccbpress') . '</div></th>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . __('Monday', 'ccbpress') . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . __('Mon', 'ccbpress') . '</div></th>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . __('Tuesday', 'ccbpress') . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . __('Tue', 'ccbpress') . '</div></th>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . __('Wednesday', 'ccbpress') . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . __('Wed', 'ccbpress') . '</div></th>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . __('Thursday', 'ccbpress') . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . __('Thu', 'ccbpress') . '</div></th>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . __('Friday', 'ccbpress') . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . __('Fri', 'ccbpress') . '</div></th>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . __('Saturday', 'ccbpress') . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . __('Sat', 'ccbpress') . '</div></th>';
	$html .= '		</tr>';

	// Setup the calendar
	$timestamp	= mktime( 0, 0, 0, $calendar_month, 1, $calendar_year);
	$maxday		= date( "t", $timestamp );
	$thismonth	= getdate( $timestamp );
	$startday	= $thismonth['wday'];

	$is_single_event_page_set = ccbpress_is_single_event_page_set();

	$last_day = 0;

	for ( $i = 0; $i < ( $maxday + $startday ); $i++ ) {

		// First day of the week
		if ( ( $last_day = $i % 7 ) == 0 ) {

			$html .= "<tr class='ccbpress-event-calendar-days'>";

		}

		// Days from the previous month
		if ( $i < $startday ) {

			$html .= "<td class='ccbpress-event-calendar-blank'></td>";

		// Days from the current month
		} else {

			$cell_class = '';

			// Check if it's today
			if ( date( 'Y-m-d', mktime( null, null, null, $calendar_month, ( $i - $startday + 1 ), $calendar_year ) ) == date( 'Y-m-d', current_time('timestamp', 0 ) ) ) {

				$cell_class .= 'ccbpress-event-calendar-today';

			}

			$event_found = false;
			$today_html = '';
			$events_per_day = 0;

			// Loop through all of the events for this month
			foreach ( $events as $event ) {

				// Compare the dates to find any events for the current day
				if ( date( 'Y-m-d', strtotime( $event->date ) ) == date( 'Y-m-d', mktime( null, null, null, $calendar_month, ( $i - $startday + 1 ), $calendar_year ) ) ) {

					$event_group_id = (string)$event->group_name['ccb_id'];
					$event_campus_id = NULL;

					if ( $campus_id == 'all' ) {
						$campus_id = 'ccbpress_all';
					}

					if ( $campus_id != 'ccbpress_all' ) {
						foreach( $group_profiles->response->groups->group as $group ) {
							if ( $group['id'] == $event_group_id ) {
								$event_campus_id = $group->campus['id'];
								break;
							}
						}
						if ($campus_id != 1) {
							foreach ($cw_group_profiles->response->groups->group as $group) {
								if ($group['id'] == $event_group_id) {
									$event_campus_id = $group->campus['id'];
									break;
								}
							}
						}
					}

					if ( $campus_id == 'ccbpress_all' || $event_campus_id == 1 || $event_campus_id == $campus_id ) {

						$start_time = $event->start_time;
						if ( $event->start_time == '00:00:00' && $event->end_time == '23:59:59' ) {
							$start_time = 'All Day';
						} else {

							if ( (string)date( 'i', strtotime( $start_time ) ) == '00' ) {

								$start_time =  (string)date( 'g', strtotime( $start_time ) ) . substr( (string)date( 'a', strtotime( $start_time ) ), 0, 1 );

							} else {

								$start_time =  (string)date( 'g:i', strtotime( $start_time ) ) . substr( (string)date( 'a', strtotime( $start_time ) ), 0, 1 );

							}

						}

						// Check if a single event page is specified in the options
						if ( $is_single_event_page_set ) {

							$today_html .= '<li><a href="' . ccbpress_get_event_url( $event->event_name['ccb_id'] ) . '" title="(' . ( $event->start_time ? $start_time : "" ) . ') ' .$event->event_name . '" class="' . ( $start_time == 'All Day' ? "ccbpress-event-calendar-all-day-event" : "" ) . '"><span class="ccbpress-event-time">' . ( $start_time == 'All Day' ? "" : $start_time ) . '</span>' . ( $start_time == 'All Day' ? "" : " " ) . $event->event_name . '</a></li>';

						} else {

							$today_html .= '<li><span title="(' . ( $event->start_time ? $start_time : "" ) . ') ' .$event->event_name . '" class="' . ( $start_time == 'All Day' ? "ccbpress-event-calendar-all-day-event" : "" ) . '"><span class="ccbpress-event-time">' . ( $start_time == 'All Day' ? "" : $start_time ) . '</span>' . ( $start_time == 'All Day' ? "" : " " ) . $event->event_name . '</span></li>';

						}

						$event_found = true;
						$events_per_day++;

					}

				}

			}

			if ( $events_per_day > 5 ) {

				$today_html .= '<div class="ccbpress-event-calendar-close"><a href="#"><span class="dashicons dashicons-no-alt"></span></a></div>';
				$today_html .= '<div class="ccbpress-event-calendar-more-link"><a href="#">+' . (string)( $events_per_day - 4 ) . ' more</a></div>';

			}

			// If no events were on the current day, give it a specific css class
			if ( !$event_found ) {

					$cell_class .= ' ccbpress-event-calendar-empty-day';

			}

			$html .= '<td class="' . trim( $cell_class ) . '"><div class="ccbpress-event-calendar-cell-container"><div class="ccbpress-event-calendar-date">'. ($i - $startday + 1) . '</div>';

			// If events were found, display them in an unordered list
			if ( $event_found ) {

				$html .= '<ul>';
				$html .= $today_html;
				$html .= '</ul>';

			}

			$html .= '</div></td>';
		}

		// If it's the last day of the week, let's close the table row
		if ( ( $i % 7 ) == 6 ) {

			$html .= "</tr>";

		}

	}

	for ( $i = 0; $i < ( 6 - $last_day ); $i++ ) {

		$html .= "<td class='ccbpress-event-calendar-blank'></td>";

	}

	if ( $last_day > 6 ) {

		$html .= '</tr>';

	}


	$html .= '</table>';
	$html .= '<div class="ccbpress-event-calendar-mobile-events"><a class="ccbpress-event-calendar-close-mobile" href="#"><div class="dashicons dashicons-no-alt"></div></a><div class="ccbpress-event-calendar-mobile-events-content"></div></div>';
	$html .= '	<div class="ccbpress-event-calendar-footer">';

	$ccbpress_show_support = '';
	if ( get_option( 'ccbpress_event_calendar_support', '1' ) == '1' ) {
		$ccbpress_show_support = 'Powered by <a href="http://ccbpress.com/">CCBPress</a>';
	}

	$html .= $ccbpress_show_support;
	$html .= '	</div>';
	$html .= '</div>';

	// Capture do_action before the calendar
	ob_start();
	do_action( 'ccbpress_event_calendar_before' );
	$html = ob_get_clean() . $html;

	// Capture do_action after the calendar
	ob_start();
	do_action( 'ccbpress_event_calendar_after' );
	$html .= ob_get_clean();

	// Return the output
	return $html;

} // ccbpress_event_list_display
remove_filter('ccbpress_event_list', 'ccbpress_event_list_display');
add_filter( 'ccbpress_event_list', 'rh_ccbpress_event_list_display' );
?>
