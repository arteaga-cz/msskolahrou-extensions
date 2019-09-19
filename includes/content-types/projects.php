<?php

/**
 * Register project post type.
 */
add_action( 'init', 'msshext_register_cpt_project' );
function msshext_register_cpt_project() {

	$archive_id = get_field( 'msshext_projects_archive_page', 'options' );
	$archive_url = msshext_get_relative_permalink( $archive_id );

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
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'with_front'		 => false,
		'rewrite'            => array(
			'slug'				=> $archive_url,
			'with_front'		=> false,
		),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
	);

	register_post_type( 'msshext_project', $args );

}

/**
 * Add options page using ACF Pro.
 */
add_action( 'acf/init', 'msshext_project_options' );
function msshext_project_options() {

	if ( !function_exists('acf_add_options_page') )
		return;

	// add sub page
	acf_add_options_sub_page( array(
		'page_title' 	=> 'Nastavení projektů',
		'menu_slug'		=> 'nastaveni-projektu',
		'menu_title' 	=> 'Nastavení projektů',
		'parent_slug' 	=> 'edit.php?post_type=msshext_project',
	) );

}

/**
 * Add missing links to breadcrumbs.
 */
add_filter( 'wpseo_breadcrumb_links', 'msshext_yoast_seo_breadcrumb_append_link_projects' );
function msshext_yoast_seo_breadcrumb_append_link_projects( $links ) {

	if ( !is_singular( 'msshext_project' ) )
		return $links;

	$new_links = array();
	$total_links = count( $links );
	$counter = 0;

	foreach ( $links as $key => $val ) {

		if ( $counter == $total_links - 1 ) {

			if ( !empty( $val['id'] ) ) {

				$archive_page_id = get_field( 'msshext_projects_archive_page', 'options' );
				$archive_page = get_post( $archive_page_id );
				$current_post = $archive_page;
				$tmp_links = array();
				$tmp_links[] = array( 'id' => $current_post->ID );

				do {
					if ( !empty( $current_post->post_parent ) ) {
						$current_post = get_post( $current_post->post_parent );
						$tmp_links[] = array( 'id' => $current_post->ID );
					}
				} while ( !empty( $current_post->post_parent > 0 ) );

				$tmp_links = array_reverse( $tmp_links );
				$new_links = array_merge( $new_links, $tmp_links );
			}
		}

		$new_links[] = $val;

		$counter++;
	}

	return $new_links;

}

/**
 * Add missing links to breadcrumbs.
 */
add_filter( 'wpseo_breadcrumb_links', 'msshext_yoast_seo_breadcrumb_append_link_event' );
function msshext_yoast_seo_breadcrumb_append_link_event( $links ) {

	if ( !is_singular( 'msshext_event' ) )
		return $links;

	$new_links = array();
	$total_links = count( $links );
	$counter = 0;

	foreach ( $links as $key => $val ) {

		if ( $counter == $total_links - 1 ) {

			if ( !empty( $val['id'] ) ) {

				$archive_page_id = get_field( 'msshext_classes_dashboard_page', 'options' );
				$archive_page = get_post( $archive_page_id );
				$current_post = $archive_page;
				$tmp_links = array();
				$tmp_links[] = array( 'id' => $current_post->ID );

				/**
				 * Add breadcrumbs for classes dashboard page and it's parents.
				 */
				do {
					if ( !empty( $current_post->post_parent ) ) {
						$current_post = get_post( $current_post->post_parent );
						$tmp_links[] = array( 'id' => $current_post->ID );
					}
				} while ( !empty( $current_post->post_parent > 0 ) );

				$tmp_links = array_reverse( $tmp_links );
				$new_links = array_merge( $new_links, $tmp_links );


				/**
				 * Add current class breadcrumb.
				 */
				$current_event = get_the_ID();
				$current_event_terms = wp_get_post_terms( $current_event, 'category' );
				$class_term_id = null;

				foreach ( $current_event_terms as $term ) {
					$is_class = get_field( 'msshext_is_class', $term );
					if ( !empty( $is_class ) ) {
						$class_term_id = $term->term_id;
						break;
					}
				}

				$class_pages = get_posts(
					array(
						'post_type' 		=> 'page',
						'poasts_per_page'	=> 1,
						'post_parent'		=> $archive_page_id,
						'tax_query'			=> array(
							array(
								'taxonomy' => 'category',
								'field'    => 'term_id',
								'terms'    => $class_term_id,
							)
						)
					)
				);

				error_log( '$class_pages: ' . print_r( $class_pages, true ) );

				if ( !empty( $class_pages[0] ) ) {
					$new_links[] = array( 'id' => $class_pages[0]->ID );
				}
			}
		}

		$new_links[] = $val;

		$counter++;
	}

	return $new_links;

}

/**
 * Register project type taxonomy.
 */
add_action( 'init', 'msshext_register_tax_project_type' );
function msshext_register_tax_project_type() {

	$labels = array(
		'name'                       => _x( 'Typy projektů', 'taxonomy general name', 'msshext' ),
		'singular_name'              => _x( 'Typ proejktu', 'taxonomy singular name', 'msshext' ),
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
		'menu_name'                  => __( 'Typy projektů', 'msshext' ),
	);

	$args = array(
		'hierarchical'          => true,
		'public'				=> true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'typ-projektu' ),
	);

	register_taxonomy( 'msshext_project_type', 'msshext_project', $args );

}
