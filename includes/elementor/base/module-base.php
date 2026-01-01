<?php
namespace MSSHEXT\Elementor\Base;

use Elementor\Core\Base\Module;
use MSSHEXT\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Module_Base extends Module {

	public function get_widgets() {
		return [];
	}

	public function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
	}

	public function init_widgets( $widgets_manager = null ) {
		$widgets_manager = $widgets_manager ?? Plugin::elementor()->widgets_manager;

		foreach ( $this->get_widgets() as $widget ) {
			$class_name = $this->get_reflection()->getNamespaceName() . '\Widgets\\' . $widget;

			$widgets_manager->register( new $class_name() );
		}
	}
}
