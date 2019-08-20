<?php
namespace MSSHEXT\Elementor\Modules\Forms;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;

use ElementorPro\Modules\Forms\Controls\Fields_Map;

use MSSHEXT\Elementor\Base\Module_Base;
//use MSSHEXT\Elementor\Forms\Classes;
use MSSHEXT\Elementor\Modules\Forms\Fields;
use MSSHEXT\Elementor\Modules\Forms\Actions;
use MSSHEXT\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Extends the functionality of Elementor Pro Form Widget
 */
class Module extends Module_Base {
	/**
	 * @var \ElementorPro\Modules\Forms\Classes\Action_Base[]
	 */
	private $form_actions = [];
	/**
	 * @var \ElementorPro\Modules\Forms\Fields\Field_Base[]
	 */
	public $field_types = [];

	public function get_name() {
		return 'forms';
	}

	public function localize_settings( $settings ) {
		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'x_field' => __( '%s Field', 'elementor-pro' ),
			],
		] );

		return $settings;
	}

	public static function find_element_recursive( $elements, $form_id ) {
		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = self::find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	/*public function register_controls() {
		$controls_manager = Plugin::elementor()->controls_manager;

		$controls_manager->register_control( Fields_Map::CONTROL_TYPE, new Fields_Map() );
	}*/

	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function forms_panel_action_data( array $data ) {
		if ( empty( $data['service'] ) ) {
			throw new \Exception( 'service_required' );
		}

		/** @var \ElementorPro\Modules\Forms\Classes\Integration_Base $integration */
		$integration = $this->get_form_actions( $data['service'] );

		if ( ! $integration ) {
			throw new \Exception( 'action_not_found' );
		}

		return $integration->handle_panel_request( $data );
	}

	public function add_form_field_type( $type, $instance ) {
		$this->field_types[ $type ] = $instance;
	}

	public function add_form_action( $id, $instance ) {
		$this->form_actions[ $id ] = $instance;
	}

	public function get_form_actions( $id = null ) {
		if ( $id ) {
			if ( ! isset( $this->form_actions[ $id ] ) ) {
				return null;
			}

			return $this->form_actions[ $id ];
		}

		return $this->form_actions;
	}

	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'pro_forms_panel_action_data', [ $this, 'forms_panel_action_data' ] );
	}

	/**
	 * Module constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_filter( 'elementor_pro/editor/localize_settings', [ $this, 'localize_settings' ] );
		//add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );
		//add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );

		//fields
		$this->add_form_field_type( 'dynamic_select', new Fields\Dynamic_Select() );

		//$this->add_component( 'recaptcha', new Classes\Recaptcha_Handler() );

		// Plugins actions
		//$this->add_form_action( 'redirect2', new Actions\Redirect2() ); //NOTE Does not work hence the approach below.
		\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( 'better_redirect', new Actions\Better_Redirect() );
	}
}
