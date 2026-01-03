<?php
namespace MSSHEXT\Elementor\Modules\DynamicTags\Tags;

use Elementor\Modules\DynamicTags\Module as TagsModule;
use ElementorPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Post Content Dynamic Tag
 *
 * Returns the formatted content of the current post.
 * Works in Loop Grid/Listing Grid context by using get_post().
 */
class Post_Content extends \Elementor\Core\DynamicTags\Tag {

	/**
	 * Get dynamic tag name.
	 *
	 * @return string Dynamic tag name.
	 */
	public function get_name(): string {
		return 'msshext-post-content';
	}

	/**
	 * Get dynamic tag title.
	 *
	 * @return string Dynamic tag title.
	 */
	public function get_title(): string {
		return esc_html__( 'Post Content (MSSH)', 'msshext' );
	}

	/**
	 * Get dynamic tag group.
	 *
	 * @return string Dynamic tag group.
	 */
	public function get_group(): string {
		return Module::POST_GROUP;
	}

	/**
	 * Get dynamic tag categories.
	 *
	 * @return array Dynamic tag categories.
	 */
	public function get_categories(): array {
		return [ TagsModule::TEXT_CATEGORY ];
	}

	/**
	 * Render tag output on the frontend.
	 *
	 * @return void
	 */
	public function render(): void {
		$post = get_post();

		if ( ! $post ) {
			return;
		}

		$content = get_the_content( null, false, $post );

		if ( empty( $content ) ) {
			return;
		}

		// Apply the_content filters for shortcodes, embeds, Gutenberg blocks, etc.
		$content = apply_filters( 'the_content', $content );

		echo $content;
	}
}
