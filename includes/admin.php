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

		add_filter( 'plugin_action_links_' . MSSHEXT_PLUGIN_BASE, [ $this, 'plugin_action_links' ] );

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
			'msshext-admin',
			MSSHEXT_ASSETS_URL . 'css/admin' . $direction_suffix . $suffix . '.css',
			[],
			MSSHEXT_VERSION
		);

		wp_enqueue_style( 'msshext-admin' );
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
		$settings_link = '<a href="edit.php?post_type=msshext_daily_menu&page=nastaveni-jidelnicku">' . __( 'Settings', 'msshext' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Register cherry category for elementor if not exists
	 *
	 * @since v1.0.0
	 * @param \Elementor\Elements_Manager $elements_manager
	 * @return void
	 */
	public function register_element_categories( $elements_manager ) {
		$elements_manager->add_category(
			'msshext',
			[
				'title' => esc_html__( 'MŠ Škola Hrou', 'msshext' ),
				'icon' => 'font',
			],
			1
		);

	}
}
