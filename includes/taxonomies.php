<?php

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
