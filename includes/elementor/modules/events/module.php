<?php
namespace MSSHEXT\Elementor\Modules\Events;

use ElementorPro\Base\Module_Base;
//use Elementor\Core\Base\Module as Module_Base;

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
