<?php

/**
 * Register daily_menu post type.
 */
add_action( 'init', 'msshext_register_cpt_daily_menu' );
function msshext_register_cpt_daily_menu() {

	$labels = array(
		'name'               => _x( 'Jídelníčky', 'post type general name', 'msshext' ),
		'singular_name'      => _x( 'Jídelníček', 'post type singular name', 'msshext' ),
		'menu_name'          => _x( 'Jídelníčky', 'admin menu', 'msshext' ),
		'name_admin_bar'     => _x( 'Jídelníček', 'add new on admin bar', 'msshext' ),
		'add_new'            => _x( 'Přidat jídelníček', 'book', 'msshext' ),
		'add_new_item'       => __( 'Přidat jídelníček', 'msshext' ),
		'new_item'           => __( 'Nový jídelníček', 'msshext' ),
		'edit_item'          => __( 'Upravit jídelníček', 'msshext' ),
		'view_item'          => __( 'Zobrazit jídelníček', 'msshext' ),
		'all_items'          => __( 'Všechny jídelníčky', 'msshext' ),
		'search_items'       => __( 'Prohledat jídelníčky', 'msshext' ),
		'parent_item_colon'  => __( 'Nadřazený jídelníček:', 'msshext' ),
		'not_found'          => __( 'Žádné jídelníčky nenalezeny.', 'msshext' ),
		'not_found_in_trash' => __( 'V koši nejsou žádné jídelníčky.', 'msshext' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'msshext' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		//'rewrite'            => array( 'slug' => 'daily_menu' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'author' ),
		//'taxonomies'		 => array( 'category' )
	);

	register_post_type( 'msshext_daily_menu', $args );

}

// Add the custom columns to the daily_menu post type:
add_filter( 'manage_msshext_daily_menu_posts_columns', 'msshext_daily_menu_columns' );
function msshext_daily_menu_columns( $columns ) {
	unset( $columns['author'] );

	$columns_new = [];

	foreach ( $columns as $key => $val ) {
		$columns_new[$key] = $val;
		if ( $key === 'title' ) {
			$columns_new['content'] = __( 'Obsah', 'msshext' );
		}
	}

	$columns_new['title'] = __( 'Datum', 'msshext' );

	return $columns_new;
}

// Add the data to the custom columns for the daily_menu post type:
add_action( 'manage_msshext_daily_menu_posts_custom_column' , 'msshext_daily_menu_column_content', 10, 2 );
function msshext_daily_menu_column_content( $column, $post_id ) {

	$post = get_post( $post_id );

	if ( $column === 'content' ) {
		echo '<strong>' . __( 'Přesnídávka:', 'msshext' ) . '</strong> ' . get_field( 'msshext_daily_menu_snack_1', $post_id ) . '<br />';
		echo '<strong>' . __( 'Polévka:', 'msshext' ) . '</strong> ' . get_field( 'msshext_daily_menu_soup', $post_id ) . '<br />';
		echo '<strong>' . __( 'Hlavní jídlo:', 'msshext' ) . '</strong> ' . get_field( 'msshext_daily_menu_lunch', $post_id ) . '<br />';
		echo '<strong>' . __( 'Svačina:', 'msshext' ) . '</strong> ' . get_field( 'msshext_daily_menu_snack_2', $post_id ) . '<br />';
		echo '<strong>' . __( 'Pitný režim:', 'msshext' ) . '</strong> ' . get_field( 'msshext_daily_menu_drinks', $post_id ) . '<br />';
	}
}

// Order the results
add_action( 'pre_get_posts', 'msshext_daily_menu_columns_orderby' );
function msshext_daily_menu_columns_orderby( $query ) {

	if ( !is_admin() )
		return;

	if ( $query->get( 'post_type' ) !== 'msshext_daily_menu' )
		return;

	$orderby = $query->get( 'orderby' );

	if ( $orderby == 'title' || empty( $orderby ) ) {

		$query->set( 'meta_query', array(
			//'relation' => 'AND',
			'date_clause' => array(
				'key' => 'msshext_daily_menu_date',
				//'value' => 'EXISTS',
			),
		) );

		$query->set( 'orderby', array(
			'date_clause' => $query->get( 'order' ),
		) );
	}
}

//add_filter( 'wp_insert_post_data', 'msshext_daily_menu_update_title', 99, 1 );
function msshext_daily_menu_update_title( $data ) {

	if ( empty( $_POST['post_type'] ) )
		return $data;

	if ( $_POST['post_type'] !== 'msshext_daily_menu' )
		return $data;

	$date_key = msshext_get_acf_key( 'msshext_daily_menu_date' );
	$date = date_i18n( 'l j. n.', strtotime( $_POST['acf'][$date_key] ) );

	$data['post_title'] = $date;
	//$data['post_name'] = $_POST['acf'][$date_key];

}

// set daily rating title
add_action( 'save_post', 'set_rating_title', 12 );
function set_rating_title( $post_id ) {

	if ( $post_id == null || empty( $_POST ) )
		return;

	if ( !isset( $_POST['post_type'] ) || $_POST['post_type'] != 'msshext_daily_menu' )
		return;

	if ( wp_is_post_revision( $post_id ) )
		$post_id = wp_is_post_revision( $post_id );

	global $post;

	if ( empty( $post ) )
		$post = get_post( $post_id );

	$date_key = msshext_get_acf_key( 'msshext_daily_menu_date' );
	$date = date_i18n( 'l j. n.', strtotime( $_POST['acf'][$date_key] ) );

	if ( $_POST['acf'][$date_key] != '' ) {
		global $wpdb;
		$where = array( 'ID' => $post_id );
		$wpdb->update( $wpdb->posts, array( 'post_title' => $date ), $where );
	}
}

/**
 * Add options page using ACF Pro.
 */
add_action( 'acf/init', 'msshext_daily_menu_options' );
function msshext_daily_menu_options() {

	if ( !function_exists('acf_add_options_page') )
		return;

	// add sub page
	acf_add_options_sub_page( array(
		'page_title' 	=> 'Nastavení jídelníčků',
		'menu_slug'		=> 'nastaveni-jidelnicku',
		'menu_title' 	=> 'Nastavení jídelníčků',
		'parent_slug' 	=> 'edit.php?post_type=msshext_daily_menu',
	) );

}

/**
 * Redirect single daily menu pages to entire week menu page.
 */
add_action( 'template_redirect', 'msshext_redirect_daily_menu_single' );
function msshext_redirect_daily_menu_single() {

	if ( !function_exists( 'get_field' ) )
		return;

	if ( !is_singular( 'msshext_daily_menu' ) )
		return;

	$menu_page_id = get_field( 'msshext_daily_menu_page', 'options' );
	if ( empty( $menu_page_id ) || !is_numeric( $menu_page_id ) )
		return;

	wp_redirect( get_permalink( $menu_page_id ) );
	die;

}

/**
 * Create RSS description.
 */
add_filter( 'the_excerpt_rss', 'msshext_daily_menu_rss_description', 10, 1 );
function msshext_daily_menu_rss_description( $output/*, $feed_type*/ ) {

	global $post;
	if ( $post->post_type !== 'msshext_daily_menu' )
		return $output;

	$new_content = __( 'Přesnídávka: ') . get_field( 'msshext_daily_menu_snack_1' ) . ' (' . get_field( 'msshext_daily_menu_snack_1_allergens' ) . ')' . ', <br>';
	$new_content.= __( 'Oběd - polévka: ') . get_field( 'msshext_daily_menu_soup' ) . ' (' . get_field( 'msshext_daily_menu_soup_allergens' ) . ')' . ', <br>';
	$new_content.= __( 'Oběd - hlavní jídlo: ') . get_field( 'msshext_daily_menu_lunch' ) . ' (' . get_field( 'msshext_daily_menu_lunch_allergens' ) . ')' . ', <br>';
	$new_content.= __( 'Svačina: ') . get_field( 'msshext_daily_menu_snack_2' ) . ' (' . get_field( 'msshext_daily_menu_snack_2_allergens' ) . ')' . ', <br>';
	$new_content.= __( 'Pitný režim: ') . get_field( 'msshext_daily_menu_drinks' ) . '<br>';

	return $output = str_replace( ']]>', ']]&gt;', $new_content );;

}


function msshext_daily_menu_download( $week = 0 ) {

	if ( empty( $_GET['msshext_action'] ) || $_GET['msshext_action'] != 'download_menu' )
		return;

	$menu_dimensions = array( 210, 297 );

	$dpi = 300;

	/**
	 * Prepare invitation email view params.
	 */
	$view_params = array(
		'menu_dimensions' 	=> $ticket_dimensions,
		'title'				=> str_replace( "\n", '<br>', $ticket->title ),
		'content'			=> str_replace( "\n", '<br>', $replaced_content->string ),
		'content_after'		=> str_replace( "\n", '<br>', $replaced_content_after->string ),
		'logo_path'			=> msshext_get_scaled_image_path( $invitation->get_logo_id(), $size = 'ticket-logo' ),
		'code'				=> $ticket->code,
		'dpi'				=> $dpi,
	);

	$html = msshext_get_menu_view( $view_params, true );

	//echo $html;
	//die;

	$defaultConfig = ( new Mpdf\Config\ConfigVariables() )->getDefaults();
	$fontDirs = $defaultConfig['fontDir'];

	$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
	$fontData = $defaultFontConfig['fontdata'];

	$mpdf = new \Mpdf\Mpdf( array(
		'fontDir' 		=> array_merge($fontDirs, [
			FLCNIN_ABSPATH . '/public/fonts',
		]),
		'fontdata' 		=> $fontData + [
			'myriadprobold' => [
				'R' 		=> 'MyriadPro-Bold.ttf',
			],
			'myriadprosemibold' => [
				'R' 		=> 'MyriadPro-Semibold.ttf',
			]
		],
		'default_font' 	=> 'myriadprobold',
		'mode' 			=> 'utf-8',
		'format' 		=> $ticket_dimensions,
		'margin_left' 	=> 0,
		'margin_right' 	=> 0,
		'margin_top' 	=> 0,
		'margin_bottom' => 0,
		'margin_header' => 0,
		'margin_footer' => 0,
		'autoPageBreak'	=> false,
		'img_dpi' 		=> $dpi
	) );
	$mpdf->WriteHTML( $html );

	$filename = 'jidelnicek-' . $ticket->code . '.pdf';

	$mpdf->Output( $filename, 'D' );

	exit;
}
