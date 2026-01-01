<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.arteaga.cz
 * @since             1.0.0
 * @package           MSSHEXT
 *
 * @wordpress-plugin
 * Plugin Name:       MŠ Škola hrou extensions
 * Description:       ms-skolahrou.cz extensions.
 * Version:           1.1.0
 * Author:            Miguel Arteaga
 * Author URI:        https://www.arteaga.cz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mmshext
 * Domain Path:       /languages
 * Requires at least: 6.0
 * Requires PHP:      8.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MSSHEXT_VERSION', '1.1.0' );

define( 'MSSHEXT__FILE__', __FILE__ );
define( 'MSSHEXT_PLUGIN_BASE', plugin_basename( MSSHEXT__FILE__ ) );
define( 'MSSHEXT_NAME', 'MŠ Škola hrou extensions' );

define( 'MSSHEXT_PATH', plugin_dir_path( MSSHEXT__FILE__ ) );
define( 'MSSHEXT_INCLUDES_PATH', plugin_dir_path( MSSHEXT__FILE__ ) . 'includes/' );
define( 'MSSHEXT_MODULES_PATH', MSSHEXT_INCLUDES_PATH . 'modules/' );
define( 'MSSHEXT_ASSETS_PATH', MSSHEXT_PATH . 'assets/' );

define( 'MSSHEXT_URL', plugins_url( '/', MSSHEXT__FILE__ ) );
define( 'MSSHEXT_ASSETS_URL', MSSHEXT_URL . 'assets/' );
define( 'MSSHEXT_INCLUDES_URL', MSSHEXT_URL . 'includes/' );
define( 'MSSHEXT_MODULES_URL', MSSHEXT_INCLUDES_URL . 'modules/' );

/**
 * Check if requirements are met,
 * load textdomain and mailn plugin file.
 *
 * @since 1.0.0
 *
 * @return void
 */
function msshext_elementor_load_plugin() {
	load_plugin_textdomain( 'msshext' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'msshext_elementor_fail_load' );

		return;
	}

	$elementor_version_required = '3.5.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'msshext_elementor_fail_load_out_of_date' );

		return;
	}

	$elementor_version_recommendation = '3.20.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_recommendation, '>=' ) ) {
		add_action( 'admin_notices', 'msshext_elementor_admin_notice_upgrade_recommendation' );
	}

	require MSSHEXT_PATH . 'plugin.php';
}
add_action( 'plugins_loaded', 'msshext_elementor_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function msshext_elementor_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message = '<p>' . __( 'Elementor Powerup is not working because you need to activate the Elementor plugin.', 'elementor-pro' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'elementor-pro' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . __( 'Elementor Powerup is not working because you need to install the Elementor plugin.', 'elementor-pro' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'elementor-pro' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function msshext_elementor_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Elementor Pro is not working because you are using an old version of Elementor.', 'elementor-pro' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'elementor-pro' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

function msshext_elementor_admin_notice_upgrade_recommendation() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'A new version of Elementor is available. For better performance and compatibility of Elementor Pro, we recommend updating to the latest version.', 'elementor-pro' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'elementor-pro' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_elementor_installed' ) ) { //TODO Refactor for Elementor Pro

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}
