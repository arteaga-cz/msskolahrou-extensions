<?php

/**
 * Register daily_menu post type.
 */
add_action( 'init', 'msshext_register_cpt_testimonial' );
function msshext_register_cpt_testimonial() {

	$internal_access = ( is_admin() && current_user_can( 'publish_posts' ) ) ? true : false;

	$labels = array(
		'name'               => _x( 'Citace', 'post type general name', 'msshext' ),
		'singular_name'      => _x( 'Citace', 'post type singular name', 'msshext' ),
		'menu_name'          => _x( 'Co o nás říkají', 'admin menu', 'msshext' ),
		'name_admin_bar'     => _x( 'Citace', 'add new on admin bar', 'msshext' ),
		'add_new'            => _x( 'Přidat citaci', 'book', 'msshext' ),
		'add_new_item'       => __( 'Přidat citaci', 'msshext' ),
		'new_item'           => __( 'Nová citace', 'msshext' ),
		'edit_item'          => __( 'Upravit citaci', 'msshext' ),
		'view_item'          => __( 'Zobrazit citaci', 'msshext' ),
		'all_items'          => __( 'Všechny citace', 'msshext' ),
		'search_items'       => __( 'Prohledat citace', 'msshext' ),
		'not_found'          => __( 'Žádné citace nenalezeny.', 'msshext' ),
		'not_found_in_trash' => __( 'V koši nejsou žádné citace.', 'msshext' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => $internal_access,
		'publicly_queryable' => $internal_access,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author' )
	);

	register_post_type( 'msshext_testimonial', $args );

}
