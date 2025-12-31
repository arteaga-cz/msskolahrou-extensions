<?php
namespace MSSHEXT\Elementor\Modules\Forms\Actions;

use Elementor\Controls_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use ElementorPro\Modules\Forms\Classes\Action_Base;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Based on ElementorPro\Modules\Forms\Actions\Redirect
 *
 * The original class does not support redirects
 * that have hyphens or spaces in shortcode value, typically dates.
 */
class Better_Redirect extends Action_Base {

	/*public function __construct() {
		error_log('__construct');
	}*/
	/**
	 * Get Name
	 *
	 * Return the action name
	 *
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return 'better_redirect';
	}

	/**
	 * Get Label
	 *
	 * Returns the action label
	 *
	 * @access public
	 * @return string
	 */
	public function get_label() {
		return __( 'Better Redirect', 'wmpup-elementor' );
	}

	/**
	 * Register Settings Section
	 *
	 * Registers the Action controls
	 *
	 * @access public
	 * @param \Elementor\Widget_Base $widget
	 */
	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_better_redirect',
			[
				'label' => esc_html__( 'Better Redirect', 'msshext' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'better_redirect_to',
			[
				'label' => esc_html__( 'Redirect To', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'https://your-link.com', 'msshext' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'render_type' => 'none',
				'classes' => 'elementor-control-direction-ltr',
			]
		);

		$widget->end_controls_section();
	}

	/**
	 * On Export
	 *
	 * Clears form settings on export
	 * @access Public
	 * @param array $element
	 */
	public function on_export( $element ) {
		unset(
			$element['settings']['better_redirect_to']
		);

		return $element;
	}

	public function replace_setting_shortcodes( $setting, $record, $urlencode = false ) {
		// Shortcode can be `[field id="fds21fd"]` or `[field title="Email" id="fds21fd"]`, multiple shortcodes are allowed
		return preg_replace_callback( '/(\[field[^]]*id="(\w+)"[^]]*\])/', function( $matches ) use ( $record, $urlencode ) {
			$value = '';
			$fields = $record->get( 'fields' );

			if ( isset( $fields[ $matches[2] ] ) ) {
				$value = $fields[ $matches[2] ]['raw_value'];
			}

			if ( $urlencode ) {
				$value = urlencode( $value );
			}
			return $value;
		}, $setting );
	}

	/**
	 * Run
	 *
	 * Runs the action after submit
	 *
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 */
	public function run( $record, $ajax_handler ) {
		$redirect_to = $record->get_form_settings( 'better_redirect_to' );
		$redirect_to = $this->replace_setting_shortcodes( $redirect_to, $record, true );

		if ( ! empty( $redirect_to ) && filter_var( $redirect_to, FILTER_VALIDATE_URL ) ) {
			$ajax_handler->add_response_data( 'redirect_url', $redirect_to );
		}
	}
}
