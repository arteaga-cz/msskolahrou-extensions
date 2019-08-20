<?php

/**
 * Register employee post type.
 */
add_action( 'init', 'msshext_register_cpt_employee' );
function msshext_register_cpt_employee() {

	$labels = array(
		'name'               => _x( 'Zaměstnanci', 'post type general name', 'msshext' ),
		'singular_name'      => _x( 'Zaměstnanec', 'post type singular name', 'msshext' ),
		'menu_name'          => _x( 'Zaměstnanci', 'admin menu', 'msshext' ),
		'name_admin_bar'     => _x( 'Zaměstnanec', 'add new on admin bar', 'msshext' ),
		'add_new'            => _x( 'Přidat zaměstnance', 'book', 'msshext' ),
		'add_new_item'       => __( 'Přidat zaměstnance', 'msshext' ),
		'new_item'           => __( 'Nový zaměstnanec', 'msshext' ),
		'edit_item'          => __( 'Upravit zaměstnance', 'msshext' ),
		'view_item'          => __( 'Zobrazit zaměstnance', 'msshext' ),
		'all_items'          => __( 'Všichni zaměstnanci', 'msshext' ),
		'search_items'       => __( 'Prohledat zaměstnance', 'msshext' ),
		'parent_item_colon'  => __( 'Nadřazený zaměstnanec:', 'msshext' ),
		'not_found'          => __( 'Žádní zaměstnanci nenalezeni.', 'msshext' ),
		'not_found_in_trash' => __( 'V koši nejsou žádní zaměstnanci.', 'msshext' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'msshext' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'employee' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'author', 'thumbnail' ),
		'taxonomies'		 => array( 'category' )
	);

	register_post_type( 'msshext_employee', $args );

}

/**
 * Register event post type.
 */
add_action( 'init', 'msshext_register_cpt_event' );
function msshext_register_cpt_event() {

	$labels = array(
		'name'               => _x( 'Události', 'post type general name', 'msshext' ),
		'singular_name'      => _x( 'Událost', 'post type singular name', 'msshext' ),
		'menu_name'          => _x( 'Události', 'admin menu', 'msshext' ),
		'name_admin_bar'     => _x( 'Událost', 'add new on admin bar', 'msshext' ),
		'add_new'            => _x( 'Přidat událost', 'book', 'msshext' ),
		'add_new_item'       => __( 'Přidat událost', 'msshext' ),
		'new_item'           => __( 'Nová událost', 'msshext' ),
		'edit_item'          => __( 'Upravit událost', 'msshext' ),
		'view_item'          => __( 'Zobrazit událost', 'msshext' ),
		'all_items'          => __( 'Všechny události', 'msshext' ),
		'search_items'       => __( 'Prohledat událost', 'msshext' ),
		'parent_item_colon'  => __( 'Nadřazená událost:', 'msshext' ),
		'not_found'          => __( 'Žádné události nenalezeny.', 'msshext' ),
		'not_found_in_trash' => __( 'V koši nejsou žádné události.', 'msshext' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'msshext' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'event' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
		'taxonomies'		 => array( 'category' )
	);

	register_post_type( 'msshext_event', $args );

}

// Add the custom columns to the event post type:
add_filter( 'manage_msshext_event_posts_columns', 'msshext_event_columns' );

function msshext_event_columns( $columns ) {
	unset( $columns['author'] );

	$columns_new = [];

	foreach ( $columns as $key => $val ) {
		$columns_new[$key] = $val;
		if ( $key === 'title' ) {
			$columns_new['datetime_start'] = __( 'Datum a čas začátku', 'msshext' );
			$columns_new['datetime_end'] = __( 'Datum a čas konce', 'msshext' );
		}
	}

	$columns_new['date'] = __( 'Datum publikace', 'msshext' );

	return $columns_new;
}

// Add the data to the custom columns for the event post type:
add_action( 'manage_msshext_event_posts_custom_column' , 'msshext_event_column_content', 10, 2 );
function msshext_event_column_content( $column, $post_id ) {

	switch ( $column ) {

		case 'datetime_start' :
			$date = get_field( 'mssh_event_date_start', $post_id );
			$time = get_field( 'mssh_event_time_start', $post_id );
			if ( !empty( $time ) )
				echo sprintf( __( '%s - %s', 'msshext' ), $date, $time );
			else
				echo $date;
			break;

		case 'datetime_end' :
			$date_start = get_field( 'mssh_event_date_start', $post_id );
			$date_end = get_field( 'mssh_event_date_end', $post_id );
			$time = get_field( 'mssh_event_time_end', $post_id );
			if ( empty( $date_end ) )
				$date_end = $date_start;

			if ( !empty( $time ) )
				echo sprintf( __( '%s - %s', 'msshext' ), $date_end, $time );
			else
				echo $date_end;
			break;
	}
}

// Make custom event columns sortable
add_filter( 'manage_edit-msshext_event_sortable_columns', 'msshext_event_sortable_columns' );
function msshext_event_sortable_columns( $columns ) {
	$columns['datetime_start'] = 'datetime_start';
	return $columns;
}

// Order the results
add_action( 'pre_get_posts', 'msshext_event_columns_orderby' );
function msshext_event_columns_orderby( $query ) {

	if ( !is_admin() )
		return;

	$orderby = $query->get( 'orderby' );

	if ( $orderby == 'datetime_start' ) {

		$query->set( 'meta_query', array(
			'relation' => 'AND',
			'date_start_clause' => array(
				'key' => 'mssh_event_date_start',
				//'value' => 'EXISTS',
			),
			'time_start_clause' => array(
				'key' => 'mssh_event_time_start',
				//'compare' => 'EXISTS',
			),
		) );

		$query->set( 'orderby', array(
			'date_start_clause' => $query->get( 'order' ),
			'time_start_clause' => $query->get( 'order' ),
		) );
	}
}

/**
 * Register project post type.
 */
add_action( 'init', 'msshext_register_cpt_project' );
function msshext_register_cpt_project() {

	$labels = array(
		'name'               => _x( 'Projekty', 'post type general name', 'msshext' ),
		'singular_name'      => _x( 'Projekt', 'post type singular name', 'msshext' ),
		'menu_name'          => _x( 'Projekty', 'admin menu', 'msshext' ),
		'name_admin_bar'     => _x( 'Projekt', 'add new on admin bar', 'msshext' ),
		'add_new'            => _x( 'Přidat projekt', 'book', 'msshext' ),
		'add_new_item'       => __( 'Přidat projekt', 'msshext' ),
		'new_item'           => __( 'Nová projekt', 'msshext' ),
		'edit_item'          => __( 'Upravit projekt', 'msshext' ),
		'view_item'          => __( 'Zobrazit projekt', 'msshext' ),
		'all_items'          => __( 'Všechny projekty', 'msshext' ),
		'search_items'       => __( 'Prohledat projekt', 'msshext' ),
		'parent_item_colon'  => __( 'Nadřazená projekt:', 'msshext' ),
		'not_found'          => __( 'Žádné projekty nenalezeny.', 'msshext' ),
		'not_found_in_trash' => __( 'V koši nejsou žádné projekty.', 'msshext' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'msshext' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'event' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
	);

	register_post_type( 'msshext_project', $args );

}
