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
		require_once MSSHEXT_INCLUDES_PATH . 'content-types/pages.php';
		require_once MSSHEXT_INCLUDES_PATH . 'content-types/events.php';
		require_once MSSHEXT_INCLUDES_PATH . 'content-types/projects.php';
		//require_once MSSHEXT_INCLUDES_PATH . 'content-types/notifications.php';
		require_once MSSHEXT_INCLUDES_PATH . 'content-types/daily_menus.php';
		require_once MSSHEXT_INCLUDES_PATH . 'content-types/testimonials.php';
		require_once MSSHEXT_INCLUDES_PATH . 'shortcodes.php';
		require_once MSSHEXT_INCLUDES_PATH . 'elementor/modules-manager.php';

		/**
		 * Composer dependencies
		 */
		require_once MSSHEXT_PATH . 'vendor/autoload.php';

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

			if ( DIRECTORY_SEPARATOR === '/' ) {
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

	public function wp_init_hooks() {
		register_taxonomy_for_object_type( 'category', 'page' );
	}

	/**
	 * Add color scheme class to <body> based on page meta or term meta.
	 *
	 * @param  array $classes Array of classes.
	 * @return array Modified array of classes.
	 */
	public function body_color_scheme_class( $classes ) {

		if ( !function_exists('get_field') )
			return $classes;

		if ( is_singular() ) {

			$post_id = get_the_ID();
			$color_scheme = get_field( 'msshext_color_scheme' );

			$terms = wp_get_post_terms( $post_id, 'category' );
			foreach ( $terms as $term ) {
				$term_color_scheme = get_term_meta( $term->term_id, 'msshext_color_scheme', true );
				if ( !empty( $term_color_scheme ) )
					$color_scheme = $term_color_scheme;
			}

			if ( !empty( $color_scheme ) ) {
				$classes[] = 'color-scheme-'.$color_scheme;
			}
		}

		return $classes;

	}

	private function register_actions_and_filters() {
		add_action( 'elementor/init', [ $this, 'on_elementor_init' ] );

		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_frontend_scripts' ] );

		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_frontend_styles' ] );

		add_action( 'elementor/document/save_version', [ $this, 'on_document_save_version' ] );

		add_action( 'init', [ $this, 'wp_init_hooks' ] );

		add_filter( 'body_class', [ $this, 'body_color_scheme_class' ] );
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {

		if ( ! is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
			return;
		}

		spl_autoload_register( [ $this, 'autoload' ] );

		$this->includes();

		$this->register_actions_and_filters();

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
