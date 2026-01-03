<?php
namespace MSSHEXT\Elementor\Modules\DynamicTags;

use Elementor\Core\Base\Module as BaseModule;
use MSSHEXT\Elementor\Modules\DynamicTags\Tags\Post_Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Dynamic Tags Module
 *
 * Registers custom dynamic tags for Elementor.
 */
class Module extends BaseModule {

	/**
	 * Get module name.
	 *
	 * @return string Module name.
	 */
	public function get_name(): string {
		return 'msshext-dynamic-tags';
	}

	/**
	 * Check if module is active.
	 *
	 * @return bool
	 */
	public static function is_active(): bool {
		return true;
	}

	/**
	 * Module constructor.
	 */
	public function __construct() {
		add_action( 'elementor/dynamic_tags/register', [ $this, 'register_tags' ] );
	}

	/**
	 * Register dynamic tags.
	 *
	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
	 * @return void
	 */
	public function register_tags( $dynamic_tags_manager ): void {
		$dynamic_tags_manager->register( new Post_Content() );
	}
}
