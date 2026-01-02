<?php
namespace MSSHEXT\Elementor\Modules\Timeline;

use ElementorPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Timeline',
		];
	}

	public function get_name() {
		return 'msshext-timeline';
	}
}
