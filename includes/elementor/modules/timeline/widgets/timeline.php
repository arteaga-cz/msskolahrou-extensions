<?php
namespace MSSHEXT\Elementor\Modules\Timeline\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Visual Timeline Widget
 *
 * @since 1.0.0
 */
class Timeline extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @return string Widget name.
	 */
	public function get_name(): string {
		return 'msshext-visual-timeline';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @return string Widget title.
	 */
	public function get_title(): string {
		return esc_html__( 'Visual Timeline', 'msshext' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @return string Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-time-line';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 1.0.0
	 * @return array Widget categories.
	 */
	public function get_categories(): array {
		return [ 'msshext' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.0.0
	 * @return array Widget keywords.
	 */
	public function get_keywords(): array {
		return [ 'timeline', 'schedule', 'history', 'events', 'vertical' ];
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 1.0.0
	 * @return array Script dependencies.
	 */
	public function get_script_depends(): array {
		return [ 'msshext-timeline' ];
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 1.0.0
	 * @return array Style dependencies.
	 */
	public function get_style_depends(): array {
		return [ 'msshext-timeline' ];
	}

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_layout_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls.
	 */
	private function register_content_controls(): void {
		$this->start_controls_section(
			'section_timeline_items',
			[
				'label' => esc_html__( 'Timeline Items', 'msshext' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'msshext' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Timeline Item', 'msshext' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'msshext' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => '',
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'msshext' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'timeline_icon',
			]
		);

		$repeater->add_control(
			'label',
			[
				'label' => esc_html__( 'Label', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'e.g., Morning', 'msshext' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'timeline_items',
			[
				'label' => esc_html__( 'Timeline Items', 'msshext' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => esc_html__( 'Timeline Item 1', 'msshext' ),
						'description' => esc_html__( 'Description for the first timeline item.', 'msshext' ),
					],
					[
						'title' => esc_html__( 'Timeline Item 2', 'msshext' ),
						'description' => esc_html__( 'Description for the second timeline item.', 'msshext' ),
					],
					[
						'title' => esc_html__( 'Timeline Item 3', 'msshext' ),
						'description' => esc_html__( 'Description for the third timeline item.', 'msshext' ),
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'msshext' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register layout controls.
	 */
	private function register_layout_controls(): void {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'msshext' ),
			]
		);

		$this->add_control(
			'layout_mode',
			[
				'label' => esc_html__( 'Layout Mode', 'msshext' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'alternate',
				'options' => [
					'alternate' => esc_html__( 'Alternate', 'msshext' ),
					'left' => esc_html__( 'Left-aligned', 'msshext' ),
				],
			]
		);

		$this->add_control(
			'starting_side',
			[
				'label' => esc_html__( 'Starting Side', 'msshext' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'msshext' ),
					'right' => esc_html__( 'Right', 'msshext' ),
				],
				'condition' => [
					'layout_mode' => 'alternate',
				],
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label' => esc_html__( 'Item Gap', 'msshext' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 48,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 */
	private function register_style_controls(): void {
		$this->register_line_style_controls();
		$this->register_icon_style_controls();
		$this->register_card_style_controls();
		$this->register_connector_style_controls();
		$this->register_label_style_controls();
		$this->register_image_style_controls();
		$this->register_title_style_controls();
		$this->register_description_style_controls();
	}

	/**
	 * Register timeline line style controls.
	 */
	private function register_line_style_controls(): void {
		$this->start_controls_section(
			'section_style_line',
			[
				'label' => esc_html__( 'Timeline Line', 'msshext' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'line_color',
			[
				'label' => esc_html__( 'Line Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-line' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'line_color_progress',
			[
				'label' => esc_html__( 'Progress Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-line-progress' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'line_width',
			[
				'label' => esc_html__( 'Line Width', 'msshext' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 3,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-line' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .msshext-timeline-line-progress' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'line_style',
			[
				'label' => esc_html__( 'Line Style', 'msshext' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'solid' => esc_html__( 'Solid', 'msshext' ),
					'dashed' => esc_html__( 'Dashed', 'msshext' ),
					'dotted' => esc_html__( 'Dotted', 'msshext' ),
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register icon circle style controls.
	 */
	private function register_icon_style_controls(): void {
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon Circle', 'msshext' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_circle_size',
			[
				'label' => esc_html__( 'Circle Size', 'msshext' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 44,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'msshext' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 60,
					],
				],
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .msshext-timeline-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_default',
			[
				'label' => esc_html__( 'Default', 'msshext' ),
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .msshext-timeline-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_scrolled',
			[
				'label' => esc_html__( 'Scrolled', 'msshext' ),
			]
		);

		$this->add_control(
			'icon_bg_color_scrolled',
			[
				'label' => esc_html__( 'Background Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-item.is-passed .msshext-timeline-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color_scrolled',
			[
				'label' => esc_html__( 'Icon Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-item.is-passed .msshext-timeline-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .msshext-timeline-item.is-passed .msshext-timeline-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icon_border_color',
			[
				'label' => esc_html__( 'Border Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'transparent',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-icon' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_border_width',
			[
				'label' => esc_html__( 'Border Width', 'msshext' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-icon' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register card style controls.
	 */
	private function register_card_style_controls(): void {
		$this->start_controls_section(
			'section_style_card',
			[
				'label' => esc_html__( 'Card', 'msshext' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_background_color',
			[
				'label' => esc_html__( 'Background Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f5f5f5',
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-card' => 'background-color: {{VALUE}};',
					// Set both border colors - CSS will hide the wrong one based on layout
					'{{WRAPPER}} .msshext-timeline-card-wrapper::before' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '8',
					'right' => '8',
					'bottom' => '8',
					'left' => '8',
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .msshext-timeline-card',
				'fields_options' => [
					'box_shadow_type' => [
						'default' => 'yes',
					],
					'box_shadow' => [
						'default' => [
							'horizontal' => 0,
							'vertical' => 2,
							'blur' => 8,
							'spread' => 0,
							'color' => 'rgba(0, 0, 0, 0.1)',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label' => esc_html__( 'Content Padding', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '16',
					'right' => '16',
					'bottom' => '16',
					'left' => '16',
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register connector arrow style controls.
	 */
	private function register_connector_style_controls(): void {
		$this->start_controls_section(
			'section_style_connector',
			[
				'label' => esc_html__( 'Connector Arrow', 'msshext' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'connector_size',
			[
				'label' => esc_html__( 'Arrow Size', 'msshext' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 4,
						'max' => 30,
					],
				],
				'default' => [
					'size' => 12,
					'unit' => 'px',
				],
				'selectors' => [
					// Set CSS custom property + border widths for CSS triangle
					'{{WRAPPER}} .msshext-timeline' => '--arrow-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .msshext-timeline-card-wrapper::before' => 'border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}}; border-left-width: {{SIZE}}{{UNIT}}; border-right-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register label style controls.
	 */
	private function register_label_style_controls(): void {
		$this->start_controls_section(
			'section_style_label',
			[
				'label' => esc_html__( 'Label', 'msshext' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .msshext-timeline-label',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_responsive_control(
			'label_spacing',
			[
				'label' => esc_html__( 'Spacing from Icon', 'msshext' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 12,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-label' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register image style controls.
	 */
	private function register_image_style_controls(): void {
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image', 'msshext' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_aspect_ratio',
			[
				'label' => esc_html__( 'Aspect Ratio', 'msshext' ),
				'type' => Controls_Manager::SELECT,
				'default' => '16-9',
				'options' => [
					'16-9' => '16:9',
					'4-3' => '4:3',
					'1-1' => '1:1',
					'original' => esc_html__( 'Original', 'msshext' ),
					'custom' => esc_html__( 'Custom', 'msshext' ),
				],
			]
		);

		$this->add_responsive_control(
			'image_custom_height',
			[
				'label' => esc_html__( 'Custom Height', 'msshext' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vh' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'condition' => [
					'image_aspect_ratio' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-image' => 'padding-bottom: 0; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register title style controls.
	 */
	private function register_title_style_controls(): void {
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'msshext' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .msshext-timeline-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register description style controls.
	 */
	private function register_description_style_controls(): void {
		$this->start_controls_section(
			'section_style_description',
			[
				'label' => esc_html__( 'Description', 'msshext' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-timeline-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .msshext-timeline-description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 1.0.0
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$items = $settings['timeline_items'];

		if ( empty( $items ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="msshext-timeline-empty">' . esc_html__( 'Add timeline items to get started', 'msshext' ) . '</div>';
			}
			return;
		}

		$layout_mode = $settings['layout_mode'];
		$starting_side = $settings['starting_side'] ?? 'left';
		$line_style = $settings['line_style'];
		$aspect_ratio = $settings['image_aspect_ratio'];

		$wrapper_classes = [
			'msshext-timeline',
			'msshext-timeline--' . $layout_mode,
		];

		if ( $layout_mode === 'alternate' ) {
			$wrapper_classes[] = 'msshext-timeline--start-' . $starting_side;
		}

		$this->add_render_attribute( 'wrapper', [
			'class' => $wrapper_classes,
			'data-layout' => $layout_mode,
			'data-start' => $starting_side,
		] );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="msshext-timeline-line msshext-timeline-line--<?php echo esc_attr( $line_style ); ?>">
				<div class="msshext-timeline-line-progress"></div>
			</div>

			<div class="msshext-timeline-items">
				<?php
				foreach ( $items as $index => $item ) {
					$this->render_timeline_item( $item, $index, $settings );
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render a single timeline item.
	 *
	 * @param array $item     Item data.
	 * @param int   $index    Item index.
	 * @param array $settings Widget settings.
	 */
	private function render_timeline_item( array $item, int $index, array $settings ): void {
		$layout_mode = $settings['layout_mode'];
		$starting_side = $settings['starting_side'] ?? 'left';
		$title_tag = $settings['title_tag'];
		$aspect_ratio = $settings['image_aspect_ratio'];

		// Determine side for this item
		if ( $layout_mode === 'left' ) {
			$side = 'right'; // Cards always on right in left-aligned mode
		} else {
			// Alternate mode
			$is_even = ( $index % 2 === 0 );
			if ( $starting_side === 'left' ) {
				$side = $is_even ? 'left' : 'right';
			} else {
				$side = $is_even ? 'right' : 'left';
			}
		}

		$item_classes = [
			'msshext-timeline-item',
			'msshext-timeline-item--' . $side,
			'elementor-repeater-item-' . $item['_id'],
		];

		$has_icon = ! empty( $item['icon']['value'] );
		$has_label = ! empty( $item['label'] );
		?>
		<div class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>">
			<div class="msshext-timeline-marker">
				<?php if ( $has_icon ) : ?>
					<div class="msshext-timeline-icon">
						<?php Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $has_label ) : ?>
					<div class="msshext-timeline-label">
						<?php echo esc_html( $item['label'] ); ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="msshext-timeline-card-wrapper">
				<div class="msshext-timeline-card">
					<?php if ( ! empty( $item['image']['url'] ) ) : ?>
						<div class="msshext-timeline-image msshext-timeline-image--<?php echo esc_attr( $aspect_ratio ); ?>">
							<?php
							echo wp_get_attachment_image(
								$item['image']['id'],
								'large',
								false,
								[ 'class' => 'msshext-timeline-image-el' ]
							);

							// Fallback for external URLs
							if ( empty( $item['image']['id'] ) && ! empty( $item['image']['url'] ) ) {
								echo '<img src="' . esc_url( $item['image']['url'] ) . '" alt="" class="msshext-timeline-image-el" />';
							}
							?>
						</div>
					<?php endif; ?>

					<div class="msshext-timeline-card-content">
						<?php if ( $has_label ) : ?>
							<div class="msshext-timeline-label msshext-timeline-label--mobile">
								<?php echo esc_html( $item['label'] ); ?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $item['title'] ) ) : ?>
							<<?php echo esc_html( $title_tag ); ?> class="msshext-timeline-title">
								<?php echo esc_html( $item['title'] ); ?>
							</<?php echo esc_html( $title_tag ); ?>>
						<?php endif; ?>

						<?php if ( ! empty( $item['description'] ) ) : ?>
							<div class="msshext-timeline-description">
								<?php echo wp_kses_post( $item['description'] ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render widget output in the editor (Backbone.js template).
	 *
	 * @since 1.0.0
	 */
	protected function content_template(): void {
		?>
		<#
		var layoutMode = settings.layout_mode;
		var startingSide = settings.starting_side || 'left';
		var lineStyle = settings.line_style;
		var aspectRatio = settings.image_aspect_ratio;
		var titleTag = settings.title_tag;

		var wrapperClasses = [
			'msshext-timeline',
			'msshext-timeline--' + layoutMode
		];

		if ( layoutMode === 'alternate' ) {
			wrapperClasses.push( 'msshext-timeline--start-' + startingSide );
		}
		#>

		<# if ( settings.timeline_items.length === 0 ) { #>
			<div class="msshext-timeline-empty"><?php echo esc_html__( 'Add timeline items to get started', 'msshext' ); ?></div>
		<# } else { #>
			<div class="{{ wrapperClasses.join(' ') }}" data-layout="{{ layoutMode }}" data-start="{{ startingSide }}">
				<div class="msshext-timeline-line msshext-timeline-line--{{ lineStyle }}">
					<div class="msshext-timeline-line-progress"></div>
				</div>

				<div class="msshext-timeline-items">
					<# _.each( settings.timeline_items, function( item, index ) {
						var side;
						if ( layoutMode === 'left' ) {
							side = 'right';
						} else {
							var isEven = ( index % 2 === 0 );
							if ( startingSide === 'left' ) {
								side = isEven ? 'left' : 'right';
							} else {
								side = isEven ? 'right' : 'left';
							}
						}

						var itemClasses = [
							'msshext-timeline-item',
							'msshext-timeline-item--' + side,
							'elementor-repeater-item-' + item._id
						];

						var hasIcon = item.icon && item.icon.value;
						var hasLabel = item.label;
					#>
						<div class="{{ itemClasses.join(' ') }}">
							<div class="msshext-timeline-marker">
								<# if ( hasIcon ) { #>
									<div class="msshext-timeline-icon">
										<# var iconHTML = elementor.helpers.renderIcon( view, item.icon, { 'aria-hidden': 'true' }, 'i', 'object' ); #>
										{{{ iconHTML.value }}}
									</div>
								<# } #>

								<# if ( hasLabel ) { #>
									<div class="msshext-timeline-label">{{ item.label }}</div>
								<# } #>
							</div>

							<div class="msshext-timeline-card-wrapper">
								<div class="msshext-timeline-card">
									<# if ( item.image && item.image.url ) { #>
										<div class="msshext-timeline-image msshext-timeline-image--{{ aspectRatio }}">
											<img src="{{ item.image.url }}" alt="" class="msshext-timeline-image-el" />
										</div>
									<# } #>

									<div class="msshext-timeline-card-content">
										<# if ( hasLabel ) { #>
											<div class="msshext-timeline-label msshext-timeline-label--mobile">{{ item.label }}</div>
										<# } #>

										<# if ( item.title ) { #>
											<{{ titleTag }} class="msshext-timeline-title">{{ item.title }}</{{ titleTag }}>
										<# } #>

										<# if ( item.description ) { #>
											<div class="msshext-timeline-description">{{{ item.description }}}</div>
										<# } #>
									</div>
								</div>
							</div>
						</div>
					<# }); #>
				</div>
			</div>
		<# } #>
		<?php
	}
}
