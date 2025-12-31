<?php
namespace MSSHEXT\Elementor\Modules\Menus;

use MSSHEXT\Elementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Menus',
		];
	}

	public function get_name() {
		return 'msshext-menus';
	}
}
