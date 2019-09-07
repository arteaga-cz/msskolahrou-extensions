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

function msshext_has_elementor( $post_id ) {
	return ! ! get_post_meta( $post_id, '_elementor_edit_mode', true );
}

function msshext_get_acf_key( $field_name ) {

	global $wpdb;

	$length = strlen( $field_name );

	$sql = "
			SELECT post_name
			FROM {$wpdb->posts}
			WHERE post_type='acf-field' AND post_excerpt='{$field_name}'
			";

	$result = $wpdb->get_var( $sql );

	return $result;
}

/**
 * Get scaled image path
 *
 * @since 1.0.0
 * @see https://wordpress.stackexchange.com/questions/182477/wp-get-attachment-image-src-and-server-path
 *
 * @param  int $attachment_id        	WP attachment ID
 * @param  string [$size = 'thumbnail'] Image size.
 * @return boolean|string  False if no such image exists, otherwise the file path.
 */
function msshext_get_scaled_image_path( $attachment_id, $size = 'thumbnail' ) {

	$file = get_attached_file( $attachment_id, true );

	if ( !wp_attachment_is_image( $attachment_id ) ) {
		return false; // the id is not referring to a media
	}

	if ( empty( $size ) || $size === 'full' ) {
		// for the original size get_attached_file is fine
		return realpath( $file );
	}

	$info = image_get_intermediate_size( $attachment_id, $size );

	if ( !is_array( $info ) || !isset( $info['file'] ) ) {
		return false; // probably a bad size argument
	}

	$realpath = realpath( str_replace( wp_basename( $file ), $info['file'], $file ) );

	return $realpath;
}
