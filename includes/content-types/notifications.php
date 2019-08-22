<?php

/**
 * Register notification post type.
 */
add_action( 'init', 'msshext_register_cpt_notification' );
function msshext_register_cpt_notification() {

	$labels = array(
		'name'               => _x( 'Notifikace', 'post type general name', 'msshext' ),
		'singular_name'      => _x( 'Notifikace', 'post type singular name', 'msshext' ),
		'menu_name'          => _x( 'Notifikace', 'admin menu', 'msshext' ),
		'name_admin_bar'     => _x( 'Notifikace', 'add new on admin bar', 'msshext' ),
		'add_new'            => _x( 'Přidat notifikace', 'book', 'msshext' ),
		'add_new_item'       => __( 'Přidat notifikaci', 'msshext' ),
		'new_item'           => __( 'Nová notifikace', 'msshext' ),
		'edit_item'          => __( 'Upravit notifikaci', 'msshext' ),
		'view_item'          => __( 'Zobrazit notifikaci', 'msshext' ),
		'all_items'          => __( 'Všechny notifikace', 'msshext' ),
		'search_items'       => __( 'Prohledat notifikace', 'msshext' ),
		'parent_item_colon'  => __( 'Nadřazená notifikace:', 'msshext' ),
		'not_found'          => __( 'Žádné notifikace nenalezeny.', 'msshext' ),
		'not_found_in_trash' => __( 'V koši nejsou žádné notifikace.', 'msshext' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'msshext' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		//'rewrite'            => array( 'slug' => 'notification' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author' ),
		'taxonomies'		 => array( 'category' )
	);

	register_post_type( 'msshext_notification', $args );

}

// Add the custom columns to the notification post type:
add_filter( 'manage_msshext_notification_posts_columns', 'msshext_notification_columns' );
function msshext_notification_columns( $columns ) {
	//unset( $columns['author'] );

	$columns_new = [];

	foreach ( $columns as $key => $val ) {
		$columns_new[$key] = $val;
		if ( $key === 'title' ) {
			$columns_new['content'] = __( 'Obsah', 'msshext' );
		}
	}

	$columns_new['categories'] = __( 'Umístění', 'msshext' );

	return $columns_new;
}

// Add the data to the custom columns for the notification post type:
add_action( 'manage_msshext_notification_posts_custom_column' , 'msshext_notification_column_content', 10, 2 );
function msshext_notification_column_content( $column, $post_id ) {

	$post = get_post( $post_id );

	switch ( $column ) {

		case 'content' :
			echo $post->post_content;
			break;
	}
}

// Make custom notification columns sortable
add_filter( 'manage_edit-msshext_notification_sortable_columns', 'msshext_notification_sortable_columns' );
function msshext_notification_sortable_columns( $columns ) {
	$columns['datetime_start'] = 'datetime_start';
	return $columns;
}

// Order the results
add_action( 'pre_get_posts', 'msshext_notification_columns_orderby' );
function msshext_notification_columns_orderby( $query ) {

	if ( !is_admin() )
		return;

	$orderby = $query->get( 'orderby' );

	if ( $orderby == 'datetime_start' ) {

		$query->set( 'meta_query', array(
			'relation' => 'AND',
			'date_start_clause' => array(
				'key' => 'mssh_notification_date_start',
				//'value' => 'EXISTS',
			),
			'time_start_clause' => array(
				'key' => 'mssh_notification_time_start',
				//'compare' => 'EXISTS',
			),
		) );

		$query->set( 'orderby', array(
			'date_start_clause' => $query->get( 'order' ),
			'time_start_clause' => $query->get( 'order' ),
		) );
	}
}
