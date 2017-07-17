<?php

function rh_event_calendar_output( $events, $args = array() ) {
	$defaults = array(
		'year' => date( 'Y' ),
		'month' => date( 'n' ),
		'group_id' => NULL,
		'campus_id' => NULL,
		'hide_campus' => '0',
		'show_support' => '1',
		'permalink' => NULL
	);

	$args = wp_parse_args( $args, $defaults );

	$ccb_data = CCBPress()->ccb->get( array(
		'cache_lifespan' => CCBPress()->ccb->cache_lifespan( 'campus_list' ),
		'query_string' => array(
			'srv' => 'campus_list'
		)
	) );

	$ccb = new CCBPress_Events_CCB_Connection();
	$ccbpress_campuses = $ccb->campus_list_array( $ccb_data );
	$ccbpress_campuses = array( 'ccbpress_all' => __( 'All Campuses', 'ccbpress-events' ) ) + $ccbpress_campuses;

	unset( $ccb );
	unset( $ccb_data );

	// Calculate the previous month and year
	$prev_month = $args['month'] - 1;
	$prev_year = $args['year'];

	if ( 0 === $prev_month ) {
		$prev_month = 12;
		$prev_year = $args['year'] - 1;
	}

	// Calculate the nex tmonth and year
	$next_month = $args['month'] + 1;
	$next_year = $args['year'];

	if ( 13 === $next_month ) {
		$next_month = 1;
		$next_year = $args['year'] + 1;
	}

	$permalink = get_permalink();

	$ccbpress_next_permalink = CCBPress_Events_Calendar::get_event_calendar_url( $next_year, $next_month, $permalink );
	$ccbpress_prev_permalink = CCBPress_Events_Calendar::get_event_calendar_url( $next_year, $next_month, $permalink );

	if ( isset( $_GET['ccbpress_event_year'] ) ) {
		$permalink = add_query_arg( 'ccbpress_event_year', $args['year'], $permalink );
	}
	if ( isset( $_GET['ccbpress_event_month'] ) ) {
		$permalink = add_query_arg( 'ccbpress_event_month', $args['month'], $permalink );
	}

	if ( isset( $_GET['ccbpress_campus_id'] ) ) {
		$ccbpress_next_permalink = add_query_arg( 'ccbpress_campus_id', $args['campus_id'], $ccbpress_next_permalink );
		$ccbpress_prev_permalink = add_query_arg( 'ccbpress_campus_id', $args['campus_id'], $ccbpress_prev_permalink );
	}

	$campus_hide_class = '';
	if ( '1' !== $args['hide_campus'] && count( $ccbpress_campuses) > 1 ) {
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
	if ( '1' !== $args['hide_campus'] && count( $ccbpress_campuses ) > 1 ) {
		$html .= '		<div class="ccbpress-event-calendar-campus">';
		$html .= '			<select>';
		foreach ( $ccbpress_campuses as $c_id => $c_name ) {
			$html .= '<option value="' . $c_id . '" ' . selected( $c_id, $args['campus_id'], false ) . ' data-url="' . esc_attr( add_query_arg( 'ccbpress_campus_id', $c_id, $permalink ) ) . '">' . $c_name . '</option>';
		}
		$html .= '			</select>';
		$html .= '		</div>';
	}
	$html .= '		<div class="ccbpress-event-calendar-month">';
	$html .= apply_filters( 'ccbpress_event_calendar_month_text', date( 'F Y', mktime( null, null, null, $args['month'], 1, $args['year'] ) ) );
	$html .= '		</div>';
	$html .= '	</div>';
	$html .= '	<table class="ccbpress-event-calendar-table">';
	$html .= '		<tr>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . esc_html__( 'Sunday', 'ccbpress-events' ) . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . esc_html__( 'Su', 'ccbpress-events' ) . '</div></th>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . esc_html__( 'Monday', 'ccbpress-events' ) . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . esc_html__( 'M', 'ccbpress-events' ) . '</div></th>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . esc_html__( 'Tuesday', 'ccbpress-events' ) . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . esc_html__( 'Tu', 'ccbpress-events' ) . '</div></th>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . esc_html__( 'Wednesday', 'ccbpress-events' ) . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . esc_html__( 'W', 'ccbpress-events' ) . '</div></th>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . esc_html__( 'Thursday', 'ccbpress-events' ) . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . esc_html__( 'Th', 'ccbpress-events' ) . '</div></th>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . esc_html__( 'Friday', 'ccbpress-events' ) . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . esc_html__( 'F', 'ccbpress-events' ) . '</div></th>';
	$html .= '			<th><div class="ccbpress-event-calendar-full-day-name">' . esc_html__( 'Saturday', 'ccbpress-events' ) . '</div><div class="ccbpress-event-calendar-mobile-day-name">' . esc_html__( 'Sa', 'ccbpress-events' ) . '</div></th>';
	$html .= '		</tr>';

	// Setup the calendar
	$timestamp = mktime( 0, 0, 0, $args['month'], 1, $args['year'] ); // First of month midnight timestamp
	$maxday = date( 't', $timestamp ); // Number of days in month
	$thismonth = getdate( $timestamp ); // Turn back into date array
	$startday = $thismonth['wday']; // Day of week of first of month indexed from 0

	$is_single_event_page_set = CCBPress_Events_Calendar::is_single_event_page_set();

	$last_day = 0;

	// Covers first to last of month plus days from last month in the first row (week) of this month
	for ( $i = 0; $i < ( $maxday + $startday ); $i++ ) {
		// First day of the week
		if ( ( $last_day = $i % 7 ) === 0 ) {
			// New row (week)
			$html .= "<tr class='ccbpress-event-calendar-days'>";
		}

		// Days from the previous month in first row (week) of this month, blank
		if ( $i < $startday ) {
			$html .= "<td class='ccbpress-event-calendar-blank'></td>";
		} else { // Days from the current month
			$cell_class = '';

			// Check if this date is today
			if ( date ( 'Y-m-d', mktime( null, null, null, $args['month'], ( $i - $startday + 1 ), $args['year'] ) ) === date( 'Y-m-d', current_time( 'timestamp', 0 ) ) ) {
				$cell_class .= 'ccbpress-event-calendar-today';
			}

			$event_found = false;
			$today_html = '';
			$events_per_day = 0;

			$group_profiles_db = new CCBPress_Group_Profiles_DB();

			// Loop through all of the events for this month
			foreach ( $events as $event ) {
				// Compare the dates to find any events for the current day
				if ( date( 'Y-m-d', strtotime( $event->date ) ) === date( 'Y-m-d', mktime( null, null, null, $args['month'], ( $i - $startday + 1 ), $args['year'] ) ) ) {
					$event_group_id = (string) $event->group_name['ccb_id'];
					$event_campus_id = null;

					if ( 'all' === $args['campus_id'] ) {
						$args['campus_id'] = 'ccbpress_all';
					}

					if ( 'ccbpress_all' !== $args['campus_id'] ) {
						if ( false !== ( $group_profile = $group_profiles_db->get( $event_group_id ) ) ) {
							$event_campus_id = $group_profile->campus_id;
						}
					}

					if ( ( is_null( $args['group_id'] ) || in_array( $event_group_id, explode( ',', $args['group_id'] ), true ) ) && ( is_null( $args['campus_id'] ) || 'ccbpress_all' === $args['campus_id'] || $event_campus_id === $args['campus_id'] || $event_campus_id === '1' ) ) {
						$start_time = $event->start_time;
						if ( '00:00:00' === (string) $event->start_time && '23:59:59' === (string) $event->end_time ) {
							$start_time = 'All Day';
						} else {
							if ( '00' === (string) date( 'i', strtotime( $start_time ) ) ) {
								$start_time = (string) date( 'g', strtotime( $start_time ) ) . substr( (string) date( 'a', strtotime( $start_time ) ), 0, 1 );
							} else {
								$start_time = (string) date( 'g:i', strtotime( $start_time ) ) . substr( (string) date( 'a', strtotime( $start_time ) ), 0, 1 );
							}
						}

						// Check if a single event page is specified in the options
						if ( $is_single_event_page_set ) {
							$today_html .= '<li><a href="' . CCBPress_Events_Calendar::get_event_url( (string) $event->event_name['ccb_id'] ) . '" title="(' . ( $event->start_time ? $start_time : '' ) . ') ' . $event->event_name . '" class="' . ( 'All Day' === $start_time ? 'ccbpress-event-calendar-all-day-event' : '' ) . '"><span class="ccbpress-event-time">' . ( 'All Day' === $start_time ? '' : $start_time ) . '</span>' . ( 'All Day' === $start_time ? '' : ' ' ) . $event->event_name . '</a></li>';
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

			// If no events were found on the current day, give it a specific css class
			if ( ! $event_found ) {
				$cell_class .= ' ccbpress-event-calendar-empty-day';
			}

			$html .= '<td class="' . trim( $cell_class ) . '"><div class="ccbpress-event-calendar-cell-container"><div class="ccbpress-event-calendar-date">' . ( $i - $startday + 1 ) . '</div>';

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
		$ccbpress_show_support = sprintf('%s <a href="https://ccbpress.com/">%s</a>', __('Powered by', 'ccbpress-events'), __('CCBPress', 'ccbpress-events') );
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
}

remove_all_filters( 'ccbpress_event_calendar_output' );
add_filter ( 'ccbpress_event_calendar_output', 'rh_event_calendar_output', 10, 2 );
