<?php
namespace MSSHEXT\Elementor\Base;

use Elementor\Core\Base\Module;
use MSSHEXT\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Module_Base extends Module {

	private static $_instances = [];

	public static function instance() {
		$class_name = get_called_class();

		if ( ! isset( self::$_instances[ $class_name ] ) ) {
			self::$_instances[ $class_name ] = new $class_name();
		}

		return self::$_instances[ $class_name ];
	}

	public static function is_active() {
		return true;
	}

	public function get_widgets() {
		return [];
	}

	protected function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
	}

	public function init_widgets( $widgets_manager ) {
		foreach ( $this->get_widgets() as $widget ) {
			$class_name = $this->get_reflection()->getNamespaceName() . '\Widgets\\' . $widget;

			$widgets_manager->register( new $class_name() );
		}
	}
}
