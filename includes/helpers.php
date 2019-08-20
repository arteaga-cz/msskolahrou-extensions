<?php

function msshext_check_event_date_and_id( $event_id, $event_date ) {

	if ( !function_exists( 'get_field' ) )
		return false;

	$post = get_post( $event_id );

	if ( !is_object( $post ) || empty( $post->ID ) )
		return false;

	$dates = get_field( 'msshext_workshop_dates', $post->ID );

	foreach ( $dates as $date ) {
		if ( $date['msshext_workshop_date'] == $event_date )
			return true;
	}

	return false;

}
