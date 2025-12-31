<?php
namespace MSSHEXT\Elementor\Modules\Events;

use MSSHEXT\Elementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Events',
		];
	}

	public function get_name() {
		return 'msshext-events';
	}
}
