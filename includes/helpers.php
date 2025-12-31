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
	if ( did_action( 'elementor/loaded' ) ) {
		return \Elementor\Plugin::$instance->db->is_built_with_elementor( $post_id );
	}
	return ! ! get_post_meta( $post_id, '_elementor_edit_mode', true );
}

function msshext_get_acf_key( $field_name ) {

	if ( function_exists( 'acf_get_field' ) ) {
		$field = acf_get_field( $field_name );
		if ( $field && isset( $field['key'] ) ) {
			return $field['key'];
		}
	}

	global $wpdb;

	$sql = $wpdb->prepare( "
			SELECT post_name
			FROM {$wpdb->posts}
			WHERE post_type='acf-field' AND post_excerpt=%s
			", $field_name );

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

/**
 * Get template parts from views folder
 *
 * @param string    $slug
 * @param string    $name
 * @param boolean   $return
 * @return object
 */
function msshext_get_view( $slug, $name = '', $view_params = array(), $return = false ) {

	if ( $return ) {
		ob_start();
		msshext_get_template_part( 'views/' . $slug . '/' . $name, $view_params );

		return ob_get_clean();
	}
	else {
		msshext_get_template_part( 'views/' . $slug . '/' . $name, $view_params );
	}
}

/**
 * Like get_template_part() lets you pass args to the template file
 * Args are available in the template as $view_params array
 * @param string filepart
 * @param mixed wp_args style argument list
 *
 * @since       1.0.0
 * @version 	1.0.0
 */
function msshext_get_template_part( $file, $view_params = array() ) {

	if ( file_exists( get_stylesheet_directory() . '/' . $file . '.php' ) ) {

		$file_path = ( get_stylesheet_directory() . '/' . $file . '.php' );

	} elseif ( file_exists( get_template_directory() . '/' . $file . '.php' ) ) {

		$file_path = realpath( get_template_directory() . '/' . $file . '.php' );

	} elseif ( file_exists( MSSHEXT_PATH . $file . '.php' ) ) {

		$file_path = realpath( MSSHEXT_PATH . $file . '.php' );

	} else {

		//return false;

	}

	$view_params = wp_parse_args( $view_params );

	if ( empty( $file_path ) )
		return false;

	ob_start();
	include( $file_path );
	$output = ob_get_clean();

	echo $output;
}

function msshext_get_formatted_date( $date, $new_format, $show_prefix = true ) {

	$prefix = '';
	$date = wp_date( 'Y-m-d', strtotime( $date ) );

	$today = new \DateTime(); // This object represents current date/time
	$today->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison

	$match_date = new \DateTime( $date );
	$match_date->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison

	if ( $show_prefix ) {

		$diff = $today->diff( $match_date );
		$diffDays = (integer)$diff->format( "%R%a" ); // Extract days count in interval

		switch ( $diffDays ) {
			case 0:
				$prefix = mb_strtoupper( __( 'Dnes', 'msshext' ) ) . ' - ';
				break;
			case -1:
				$prefix = mb_strtoupper( __( 'Včera', 'msshext' ) ) . ' - ';
				break;
			case +1:
				$prefix = mb_strtoupper( __( 'Zítra', 'msshext' ) ) . ' - ';
				break;
		}

	}

	return $prefix . wp_date( $new_format, strtotime( $match_date->format( 'Y-m-d' ) ) );
}
