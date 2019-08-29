<?php

add_shortcode( 'msshext_forced_excerpts', 'msshext_shortcode_forced_excerpts' );
function msshext_shortcode_forced_excerpts() {
	$post_id = get_the_ID();
	$post = get_post( $post_id );

	if ( !empty( $post->post_excerpt ) )
		return wp_trim_words( $post->post_excerpt );

	return wp_trim_words( $post->post_content );
}

add_shortcode( 'msshext_project_dates', 'msshext_shortcode_project_dates' );
function msshext_shortcode_project_dates() {
	$post_id = get_the_ID();

	$start_date = get_field( 'msshext_project_year_start', $post_id );
	$end_date = get_field( 'msshext_project_year_end', $post_id );

	if ( empty( $end_date ) )
		return $start_date;

	return $start_date . ' - ' . $end_date;
}

add_shortcode( 'msshext_post_content', 'msshext_shortcode_post_content' );
function msshext_shortcode_post_content() {
	$post_id = get_the_ID();
	$post = get_post( $post_id );

	return apply_filters( 'the_content', $post->post_content );
}

