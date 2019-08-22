<?php
namespace MSSHEXT;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Main class plugin
 */
class Plugin {

	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	public $modules_manager;

	/**
	 * @var Editor
	 */
	public $editor;

	/**
	 * @var Admin
	 */
	public $admin;

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Something went wrong.', 'msshext' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Something went wrong.', 'msshext' ), '1.0.0' );
	}

	/**
	 * @return \Elementor\Plugin
	 */

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function includes() {
		require_once MSSHEXT_INCLUDES_PATH . 'helpers.php';
		//require_once MSSHEXT_INCLUDES_PATH . 'content-types/employees.php';
		require_once MSSHEXT_INCLUDES_PATH . 'content-types/events.php';
		require_once MSSHEXT_INCLUDES_PATH . 'content-types/projects.php';
		//require_once MSSHEXT_INCLUDES_PATH . 'content-types/notifications.php';
		require_once MSSHEXT_INCLUDES_PATH . 'content-types/daily_menus.php';
		require_once MSSHEXT_INCLUDES_PATH . 'shortcodes.php';
		require_once MSSHEXT_INCLUDES_PATH . 'elementor/modules-manager.php';

		if ( is_admin() ) {
			require_once MSSHEXT_INCLUDES_PATH . 'admin.php';
		}
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$class_to_load = $class;

		if ( ! class_exists( $class_to_load ) ) {

			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);

			if ( DIRECTORY_SEPARATOR == '/' ) {
				$filename = str_replace( 'msshext/', '', $filename );
			} else {
				$filename = str_replace( 'msshext\\', '', $filename );
			}
			$filename = MSSHEXT_INCLUDES_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}
	}

	public function enqueue_frontend_styles() {
		//$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$suffix = '';

		$direction_suffix = is_rtl() ? '-rtl' : '';

		$frontend_file_name = 'frontend' . $direction_suffix . $suffix . '.css';

		$frontend_file_url = MSSHEXT_ASSETS_URL . 'css/' . $frontend_file_name;

		wp_enqueue_style(
			'msshext-frontend',
			$frontend_file_url,
			[],
			MSSHEXT_VERSION
		);
	}

	public function enqueue_frontend_scripts() {
		//$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$suffix = '';

		wp_enqueue_script(
			'msshext-frontend',
			MSSHEXT_URL . 'assets/js/frontend' . $suffix . '.js',
			[
				'elementor-frontend-modules'
			],
			MSSHEXT_VERSION,
			true
		);

		/*$locale_settings = [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'msshext-frontend' ),
		];*/

		/**
		 * Localize frontend settings.
		 *
		 * Filters the frontend localized settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $locale_settings Localized settings.
		 */
		/*$locale_settings = apply_filters( 'msshext/frontend/localize_settings', $locale_settings );

		if ( !empty( $locale_settings ) )
			wp_localize_script( 'msshext-frontend', 'MsshextElementorConfig', $locale_settings );*/
	}

	public function register_frontend_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		/*wp_register_script(
			'smartmenus',
			MSSHEXT_URL . 'assets/lib/smartmenus/jquery.smartmenus' . $suffix . '.js',
			[
				'jquery',
			],
			'1.0.1',
			true
		);*/

	}

	public function on_elementor_init() {
		$this->modules_manager = new Elementor\Modules_Manager();

		/**
		 * Elementor Pro init.
		 *
		 * Fires on Elementor Pro init, after Elementor has finished loading but
		 * before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		do_action( 'msshext/init' );
	}

	/**
	 * @param \Elementor\Core\Base\Document $document
	 */
	public function on_document_save_version( $document ) {
		$document->update_meta( '_msshext_version', MSSHEXT_VERSION );
	}

	private function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'on_elementor_init' ] );

		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_frontend_scripts' ] );

		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_frontend_styles' ] );

		add_action( 'elementor/document/save_version', [ $this, 'on_document_save_version' ] );
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {

		if ( ! is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) { //NOTE Refactor so that Elementor Pro is not needed.
			return;
		}

		spl_autoload_register( [ $this, 'autoload' ] );

		$this->includes();

		$this->setup_hooks();

		if ( is_admin() ) {
			$this->admin = new Admin();
		}
	}

	final public static function get_title() {
		return MSSHEXT_NAME;
	}
}

if ( ! defined( 'MSSHEXT_TESTS' ) ) {
	// In tests we run the instance manually.
	Plugin::instance();
}
