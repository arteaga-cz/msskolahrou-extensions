<?php

/**
 * Register daily_menu post type.
 */
add_action( 'init', 'msshext_register_cpt_testimonial' );
function msshext_register_cpt_testimonial() {

	$labels = array(
		'name'               => _x( 'Citace', 'post type general name', 'msshext' ),
		'singular_name'      => _x( 'Citace', 'post type singular name', 'msshext' ),
		'menu_name'          => _x( 'Citace', 'admin menu', 'msshext' ),
		'name_admin_bar'     => _x( 'Citace', 'add new on admin bar', 'msshext' ),
		'add_new'            => _x( 'Přidat citaci', 'book', 'msshext' ),
		'add_new_item'       => __( 'Přidat citaci', 'msshext' ),
		'new_item'           => __( 'Nov8 citace', 'msshext' ),
		'edit_item'          => __( 'Upravit citaci', 'msshext' ),
		'view_item'          => __( 'Zobrazit citaci', 'msshext' ),
		'all_items'          => __( 'Všechny citace', 'msshext' ),
		'search_items'       => __( 'Prohledat citace', 'msshext' ),
		//'parent_item_colon'  => __( 'Nadřazený jídelníček:', 'msshext' ),
		'not_found'          => __( 'Žádné citace nenalezeny.', 'msshext' ),
		'not_found_in_trash' => __( 'V koši nejsou žádné citace.', 'msshext' )
	);

	$args = array(
		'labels'             => $labels,
		//'description'        => __( 'Description.', 'msshext' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		//'rewrite'            => array( 'slug' => 'daily_menu' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author' ),
		//'taxonomies'		 => array( 'category' )
	);

	register_post_type( 'msshext_testimonial', $args );

}
