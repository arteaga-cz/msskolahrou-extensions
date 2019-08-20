<?php
namespace MSSHEXT\Elementor\Modules\Forms\Fields;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

use ElementorPro\Modules\Forms\Classes;
use ElementorPro\Modules\Forms\Fields\Field_Base;

use MSSHEXT\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Dynamic select field class.
 *
 * @see https://github.com/elementor/elementor/issues/6139
 */
class Dynamic_Select extends Field_Base {

	public function __construct() {
		parent::__construct();
		add_action( 'elementor/preview/init', [ $this, 'editor_inline_JS' ] ); //TODO Move to separate JS file.
	}

	public function get_type() {
		return 'dynamic_select';
	}

	public function get_name() {
		return __( 'Dynamic Select', 'elementor-pro' );
	}

	public function render( $item, $item_index, $form ) {
		$item['field_options'] = $this->get_options( $item );
		echo $this->make_select_field( $item, $item_index, $form );
	}

	protected function get_options( $item ) {

		$data = '';

		if ( $item['data_source'] == 'post_meta' ) {
			$data = get_post_meta( get_the_ID(), $item['data_key'], true );
		}

		if ( $item['data_source'] == 'option' ) {
			$data = get_option( $item['data_key'] );
		}

		if ( $item['data_source'] == 'function' ) {
			$data = call_user_func( $item['function_name'] );
		}

		if ( $item['data_source'] == 'acf_repeater_post' || $item['data_source'] == 'acf_repeater_options' ) {

			$source = 'options';
			if (  $item['data_source'] == 'acf_repeater_post' )
				$source = get_the_ID();

			$options = [];
			$rows = get_field( $item['data_key'], $source );
			$option_value = $item['repeater_col_1'];
			$option_label = $item['repeater_col_2'];
			if ( empty( $option_label ) )
				$option_label = $option_value;

			foreach( $rows as $row ) {
				$options[ $row[$option_value] ] = $row[$option_label];
			}

			$data_arr = [];
			if ( is_array( $options ) ) {
				foreach( $options as $key => $val ) {
					$data_arr[] = $val.'|'.$key;
				}
				$data = implode( PHP_EOL, $data_arr );
			}
		}

		return $data;
	}

	/**
	 * Taken from Elementor Pro Form_Base Class.
	 *
	 * @param  array $item Field params.
	 * @param  integer $i    Field index within the form.
	 * @param  object $form Elementor form object.
	 * @return string   Wrapped select field HTML.
	 */
	protected function make_select_field( $item, $i, $form ) {

		$form->add_render_attribute(
			[
				'select-wrapper' . $i => [
					'class' => [
						'elementor-field',
						'elementor-select-wrapper',
						esc_attr( $item['css_classes'] ),
					],
				],
				'select' . $i => [
					'name' => $form->get_attribute_name( $item ) . ( ! empty( $item['allow_multiple'] ) ? '[]' : '' ),
					'id' => $form->get_attribute_id( $item ),
					'class' => [
						'elementor-field-textual',
						'elementor-size-' . $item['input_size'],
					],
				],
			]
		);

		if ( $item['required'] ) { //TODO Form_Base->add_required_attribute() is protected.
			$form->add_render_attribute( 'select', 'required', 'required' );
			$form->add_render_attribute( 'select', 'aria-required', 'true' );
		}

		if ( $item['allow_multiple'] ) {
			$form->add_render_attribute( 'select' . $i, 'multiple' );
			if ( ! empty( $item['select_size'] ) ) {
				$form->add_render_attribute( 'select' . $i, 'size', $item['select_size'] );
			}
		}

		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );
		if ( ! $options ) {
			return '';
		}

		ob_start();
?>
<div <?php echo $form->get_render_attribute_string( 'select-wrapper' . $i ); ?>>
	<select <?php echo $form->get_render_attribute_string( 'select' . $i ); ?>>
		<?php
		foreach ( $options as $key => $option ) {
			$option_id = $item['custom_id'] . $key;
			$option_value = esc_attr( $option );
			$option_label = esc_html( $option );

			if ( false !== strpos( $option, '|' ) ) {
				list( $label, $value ) = explode( '|', $option );
				$option_value = esc_attr( $value );
				$option_label = esc_html( $label );
			}

			$form->add_render_attribute( $option_id, 'value', $option_value );

			if ( ! empty( $item['field_value'] ) && $option_value === $item['field_value'] ) {
				$form->add_render_attribute( $option_id, 'selected', 'selected' );
			}
			echo '<option ' . $form->get_render_attribute_string( $option_id ) . '>' . $option_label . '</option>';
		}
		?>
	</select>
</div>
<?php

		$select = ob_get_clean();
		return $select;
	}

	/**
	 * @param Widget_Base $widget
	 */
	public function update_controls( $widget ) {

		$elementor = Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = [
			'data_source' => [
				'name' => 'data_source',
				'label' => __( 'Data Source', 'wpup-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'acf_repeater_post' => __( 'ACF Repeater Post', 'wpup-elementor' ),
					'acf_repeater_options' => __( 'ACF Repeater Options', 'wpup-elementor' ),
					'post_meta' => __( 'Post Meta', 'wpup-elementor' ),
					'option' => __( 'Site Option', 'wpup-elementor' ),
					'function' => __( 'Custom function', 'wpup-elementor' ),
				],
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
				'description' => __( 'If the data is pulled from a text field, enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Name|f_name', 'wpup-elementor' ),
			],

			'first_option' => [
				'name' => 'first_option',
				'label' => __( 'First Option', 'wpup-elementor' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],

			'data_key' => [
				'name' => 'data_key',
				'label' => __( 'Data Key', 'wpup-elementor' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'field_type' => $this->get_type(),
					'data_source' => ['acf_repeater_post', 'acf_repeater_options', 'meta', 'option'],
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],

			'repeater_col_1' => [
				'name' => 'repeater_col_1',
				'label' => __( 'Repeater Col 1 (Option Label)', 'wpup-elementor' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'field_type' => $this->get_type(),
					'data_source' => ['acf_repeater_post', 'acf_repeater_options'],
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
				'description' => __( 'Enter the key used in repeater. The values from repeater will be used as option Value and also as option Label if Repeater Col 2 is not set.', 'wpup-elementor' ),
			],

			'repeater_col_2' => [
				'name' => 'repeater_col_2',
				'label' => __( 'Repeater Col 2 (Option Key)', 'wpup-elementor' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'field_type' => $this->get_type(),
					'data_source' => ['acf_repeater_post', 'acf_repeater_options'],
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
				'description' => __( 'Enter the key used in repeater. The values from repeater will be used as option Label.', 'wpup-elementor' ),
			],

			'function_name' => [
				'name' => 'function_name',
				'label' => __( 'Function Name', 'wpup-elementor' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'field_type' => $this->get_type(),
					'data_source' => ['function'],
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls );
		$widget->update_control( 'form_fields', $control_data );
	}

	public function editor_inline_JS() {

		add_action( 'wp_footer', function() { //TODO Move to .js file and use elementor/editor/before_enqueue_scripts hook.
			?>
			<script>
				var ElementorFormDynamicSelectField  = ElementorFormDynamicSelectField  || {};
				jQuery( document ).ready( function( $ ) {
					"use strict";

					ElementorFormDynamicSelectField = {

						onReady: function( callback ) {
						}, //TODO Refactor

						renderField: function( inputField, item, i, settings ) {
							var itemClasses = item.css_classes,
								required = '',
								fieldName = 'form_field_',
								fieldHtml = '<div class="elementor-field elementor-select-wrapper ">';

							if ( item.required ) {
								required = 'required';
							}

							fieldHtml += '<select class="elementor-field-textual elementor-field ' + itemClasses + ' elementor-size-' + settings.input_size + '" name="' + fieldName + '" id="form_field_' + i + '" ' + required + '>';
							fieldHtml += '<option>Option 1</option>';
							fieldHtml += '<option>Option 2</option>';
							fieldHtml += '</select>';
							fieldHtml += '</div>';

							return fieldHtml;
						},

						init: function () {
							elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/dynamic_select', ElementorFormDynamicSelectField.renderField, 10, 4 );
						}
					};

					ElementorFormDynamicSelectField.init();

				} );
			</script>
			<?php
		} );
	}

	public function validation( $field, Classes\Form_Record $record, Classes\Ajax_Handler $ajax_handler ) {

		/*error_log( '$field: ' . print_r( $field, true ) );
		error_log( '$record: ' . print_r( $record, true ) );
		error_log( '$ajax_handler: ' . print_r( $ajax_handler, true ) );*/

		/*if ( ! empty( $field['field_max'] ) && $field['field_max'] < (int) $field['value'] ) {
			$ajax_handler->add_error( $field['id'], sprintf( __( 'The value must be less than or equal to %s', 'elementor-pro' ), $field['field_max'] ) );
		}

		if ( ! empty( $field['field_min'] ) && $field['field_min'] > (int) $field['value'] ) {
			$ajax_handler->add_error( $field['id'], sprintf( __( 'The value must be greater than or equal %s', 'elementor-pro' ), $field['field_min'] ) );
		}*/
	}

	public function sanitize_field( $value, $field ) {
		return intval( $value );
	}
}
