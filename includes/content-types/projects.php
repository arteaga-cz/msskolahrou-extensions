<?php

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
