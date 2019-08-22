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
 * Register employee cat taxonomy.
 */
add_action( 'init', 'msshext_register_tax_employee_cat' );
function msshext_register_tax_employee_cat() {

	$labels = array(
		'name'                       => _x( 'Typy zaměstnanců', 'taxonomy general name', 'msshext' ),
		'singular_name'              => _x( 'Typ zaměstnance', 'taxonomy singular name', 'msshext' ),
		'search_items'               => __( 'Prohledat typy', 'msshext' ),
		'popular_items'              => __( 'Používané typy', 'msshext' ),
		'all_items'                  => __( 'Všechny typy', 'msshext' ),
		'parent_item'                => __( 'Nadřazený typ', 'msshext' ),
		'parent_item_colon'          => __( 'Nadřazený typ:', 'msshext' ),
		'edit_item'                  => __( 'Upravit typ', 'msshext' ),
		'update_item'                => __( 'Aktualizovat typ', 'msshext' ),
		'add_new_item'               => __( 'Přidat typ', 'msshext' ),
		'new_item_name'              => __( 'Název nového typu', 'msshext' ),
		'separate_items_with_commas' => __( 'Oddělte typy čárkami', 'msshext' ),
		'add_or_remove_items'        => __( 'Přidat nebo odebrat typy', 'msshext' ),
		'choose_from_most_used'      => __( 'Vyberte z nejpoužívanějších typů', 'msshext' ),
		'not_found'                  => __( 'Žádné typy nenalezeny.', 'msshext' ),
		'menu_name'                  => __( 'Typy zaměstnanců', 'msshext' ),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'typ-zamestnance' ),
	);

	register_taxonomy( 'msshext_employee_cat', 'msshext_employee', $args );

}
