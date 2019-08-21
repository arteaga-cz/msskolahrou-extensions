<?php
namespace MSSHEXT;

use Elementor\Utils as ElementorUtils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Admin {

	/**
	 * Admin constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_filter( 'plugin_action_links_' . ELEMENTOR_PLUGIN_BASE, [ $this, 'plugin_action_links' ], 50 );
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );

		add_action( 'elementor/elements/categories_registered', array( $this, 'register_element_categories' ) );
	}

	/**
	 * Enqueue admin styles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		$suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		$direction_suffix = is_rtl() ? '-rtl' : '';

		wp_register_style(
			'elementor-pro-admin',
			MSSHEXT_ASSETS_URL . 'css/admin' . $direction_suffix . $suffix . '.css',
			[],
			MSSHEXT_VERSION
		);

		wp_enqueue_style( 'elementor-pro-admin' );
	}

	public function enqueue_scripts() {
		$suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		wp_enqueue_script(
			'msshext-admin',
			MSSHEXT_ASSETS_URL . 'js/admin' . $suffix . '.js',
			[
				'elementor-common',
			],
			MSSHEXT_VERSION,
			true
		);

		$locale_settings = [];

		/**
		 * Localize admin settings.
		 *
		 * Filters the admin localized settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $locale_settings Localized settings.
		 */
		$locale_settings = apply_filters( 'msshext/admin/localize_settings', $locale_settings );

		if ( !empty( $locale_settings ) )
			wp_localize_script( 'msshext-admin', 'MsshextConfig', $locale_settings );
	}

	public function plugin_action_links( $links ) {
		return $links;
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( MSSHEXT_PLUGIN_BASE === $plugin_file ) {
			$plugin_slug = basename( MSSHEXT__FILE__, '.php' );
			$plugin_name = MSSHEXT_NAME;
		}

		return $plugin_meta;
	}

	/**
	 * Register cherry category for elementor if not exists
	 *
	 * @since v1.0.0
	 * @return void
	 */
	public function register_element_categories( $elements_manager ) {
		$elements_manager->add_category(
			'msshext',
			[
				'title' => __( 'MŠ Škola Hrou', 'msshext' ),
				'icon' => 'font',
			],
			1
		);

	}
}
