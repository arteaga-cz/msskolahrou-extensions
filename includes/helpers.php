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

/**
 * Get relative URL from full URL or page/post permalink.
 *
 * @param  int|string $var Page/post ID or absolute URL.
 * @return string Relative URL.
 */
function msshext_get_relative_permalink( $var, $remove_slashes = true ) {

	if ( is_numeric( $var ) ) {
		$url = get_permalink( $var );
	} else {
		$url = $var;
	}

	if ( $remove_slashes ) {
		$url = rtrim( $url, '/' );
		$url = ltrim( $url, '/' );
	}

	return str_replace( home_url(), '', $url );
}
