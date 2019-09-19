<?php

/**
 * Add options page using ACF Pro.
 */
add_action( 'acf/init', 'msshext_page_options' );
function msshext_page_options() {

	if ( !function_exists('acf_add_options_page') )
		return;

	// add sub page
	acf_add_options_sub_page( array(
		'page_title' 	=> 'Nastavení stránek',
		'menu_slug'		=> 'nastaveni-stranek',
		'menu_title' 	=> 'Nastavení stránek',
		'parent_slug' 	=> 'edit.php?post_type=page',
	) );

}
