<?php
namespace MSSHEXT\Elementor\Modules\Events\Widgets;

use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use ElementorPro\Modules\QueryControl\Module as Module_Query;
//use ElementorPro\Modules\QueryControl\Controls\Group_Control_Related;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * @since 1.0.0
 */
class Events extends Widget_Base {

	/**
	 * @var \WP_Query
	 */
	private $_query = null;

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'msshext-events';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MŠ Události', 'msshext' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'loop', 'posts', 'list' ];
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'msshext' ];
	}

	protected function get_terms() {
		$terms = get_terms( [
			'taxonomy' => 'category',
			'hide_empty' => false,
		] );

		$options = [ '' => '' ];

		foreach ( $terms as $term ) {
			$options[ $term->term_id ] = $term->name;
		}

		return $options;
	}

	/**
	 * Register event widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		/**
		 * Content - EVENTS
		 */

		$this->start_controls_section(
			'section_events',
			[
				'label' => esc_html__( 'Events', 'msshext' ),
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Items to load', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => -1,
			]
		);


		$this->add_control(
			'items_to_show',
			[
				'label' => esc_html__( 'Items to show', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => 2,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'msshext' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'msshext' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'msshext' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'msshext' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'msshext' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);

		$this->add_control(
			'date_format',
			[
				'label' => esc_html__( 'Event Date Format', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'j. n.',
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

		$this->add_control(
			'hr_1',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'_timing_col_inline_size',
			[
				'label' => esc_html__( 'Date Column Width', 'msshext' ) . ' (%)',
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'max' => 100,
				'required' => true,
				'device_args' => [
					Controls_Stack::RESPONSIVE_TABLET => [
						'max' => 100,
						'required' => false,
					],
					Controls_Stack::RESPONSIVE_MOBILE => [
						'max' => 100,
						'required' => false,
					],
				],
				'min_affected_device' => [
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-event-timing' => 'width: {{VALUE}}%',
				],
			]
		);

		$this->add_responsive_control(
			'_desc_col_inline_size',
			[
				'label' => esc_html__( 'Content Column Width', 'msshext' ) . ' (%)',
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'max' => 100,
				'required' => true,
				'device_args' => [
					Controls_Stack::RESPONSIVE_TABLET => [
						'max' => 100,
						'required' => false,
					],
					Controls_Stack::RESPONSIVE_MOBILE => [
						'max' => 100,
						'required' => false,
					],
				],
				'min_affected_device' => [
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-event-desc' => 'width: {{VALUE}}%',
				],
			]
		);

		$this->add_control(
			'hr_2',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'group_by_month',
			[
				'label' => esc_html__( 'Seskupit podle měsíce', 'msshext' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'month_tag',
			[
				'label' => esc_html__( 'Month HTML Tag', 'msshext' ),
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
				'condition' => [
					'group_by_month' => 'yes',
				],
			]
		);

		$this->add_control(
			'hr_3',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'readmore_text',
			[
				'label' => esc_html__( 'Read More link text', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Více informací', 'msshext' ),
			]
		);

		$this->add_control(
			'readmore_selected_icon',
			[
				'label' => esc_html__( 'Read More Icon', 'msshext' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'fa4compatibility' => 'icon',
			]
		);

		$this->add_control(
			'readmore_icon_align',
			[
				'label' => esc_html__( 'Read More Icon Position', 'msshext' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'msshext' ),
					'right' => esc_html__( 'After', 'msshext' ),
				],
				'condition' => [
					'readmore_selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'readmore_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'msshext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr_4',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'terms',
			[
				'label' => esc_html__( 'Category', 'msshext' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [],
				'options' => $this->get_terms(),
				'multiple' => true,
				/*'condition' => [
					'show_filter_bar' => 'yes',
					'posts_post_type!' => 'by_id',
				],*/
			]
		);

		$this->add_control(
			'terms_relation',
			[
				'label' => esc_html__( 'Terms Relation', 'msshext' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'OR',
				'options' => [
					'OR' => esc_html__( 'OR', 'msshext' ),
					'AND' => esc_html__( 'AND', 'msshext' ),
				],
			]
		);

		$this->add_control(
			'timeframe',
			[
				'label' => esc_html__( 'Timeframe', 'msshext' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => [
					'all' => esc_html__( 'All', 'msshext' ),
					'future' => esc_html__( 'Future', 'msshext' ),
					'past' => esc_html__( 'Past', 'msshext' ),
				],
			]
		);

		$this->add_control(
			'hr_5',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'no_posts_message',
			[
				'label' => esc_html__( 'No posts found message', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Zatím žádné události.', 'msshext' ),
			]
		);

		$this->end_controls_section();

		/**
		 * Content - BUTTON
		 */

		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__( 'Button', 'msshext' ),
			]
		);

		$this->add_control(
			'button_type',
			[
				'label' => esc_html__( 'Type', 'msshext' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'msshext' ),
					'info' => esc_html__( 'Info', 'msshext' ),
					'success' => esc_html__( 'Success', 'msshext' ),
					'warning' => esc_html__( 'Warning', 'msshext' ),
					'danger' => esc_html__( 'Danger', 'msshext' ),
				],
				'prefix_class' => 'elementor-button-',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Text', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Click here', 'msshext' ),
				'placeholder' => esc_html__( 'Click here', 'msshext' ),
			]
		);

		$this->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Link', 'msshext' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'msshext' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'button_align',
			[
				'label' => esc_html__( 'Alignment', 'msshext' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'msshext' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'msshext' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'msshext' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				//'prefix_class' => 'elementor%s-button-align-',
				'default' => '',
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => esc_html__( 'Size', 'msshext' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'button_selected_icon',
			[
				'label' => esc_html__( 'Icon', 'msshext' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'fa4compatibility' => 'icon',
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'msshext' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'msshext' ),
					'right' => esc_html__( 'After', 'msshext' ),
				],
				'condition' => [
					'button_selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'msshext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_view',
			[
				'label' => esc_html__( 'View', 'msshext' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label' => esc_html__( 'Button ID', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'msshext' ),
				'label_block' => false,
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'msshext' ),
				'separator' => 'before',

			]
		);

		$this->end_controls_section();

		/**
		 * CSS - EVENTS
		 */

		$this->start_controls_section(
			'section_style_event_card',
			[
				'label' => esc_html__( 'Event card', 'msshext' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'event_card_background_color',
			[
				'label' => esc_html__( 'Background Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-event-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'event_card_border',
				'selector' => '{{WRAPPER}} .msshext-event-wrapper',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'event_card_radius',
			[
				'label' => esc_html__( 'Border Radius', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-event-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'event_card_margin',
			[
				'label' => esc_html__( 'Card Margin', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-event-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'event_card_date_col_padding',
			[
				'label' => esc_html__( 'Date Column Padding', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-event-timing' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'event_card_desc_col_padding',
			[
				'label' => esc_html__( 'Desc. Column Padding', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-event-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'event_card_box_shadow',
				'selector' => '{{WRAPPER}} .msshext-event-wrapper',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_event_date',
			[
				'label' => esc_html__( 'Event date', 'msshext' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => esc_html__( 'Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .msshext-event-timing' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .msshext-event-timing',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'date_margin',
			[
				'label' => esc_html__( 'Margin', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .msshext-event-timing' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_event_title',
			[
				'label' => esc_html__( 'Event title', 'msshext' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .msshext-event-title' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .msshext-event-title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .msshext-event-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_event_excerpt',
			[
				'label' => esc_html__( 'Event content', 'msshext' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' => esc_html__( 'Text Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .msshext-event-desc .msshext-event-excerpt' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .msshext-event-desc .msshext-event-excerpt',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'excerpt_margin',
			[
				'label' => esc_html__( 'Margin', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .msshext-event-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_readmore_style',
			[
				'label' => esc_html__( 'Read More', 'msshext' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.msshext-event-readmore, {{WRAPPER}} .msshext-event-readmore',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'readmore_text_shadow',
				'selector' => '{{WRAPPER}} a.msshext-event-readmore, {{WRAPPER}} .msshext-event-readmore',
			]
		);

		$this->start_controls_tabs( 'tabs_readmore_style' );

		$this->start_controls_tab(
			'tab_readmore_normal',
			[
				'label' => esc_html__( 'Normal', 'msshext' ),
			]
		);

		$this->add_control(
			'readmore_text_color',
			[
				'label' => esc_html__( 'Text Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.msshext-event-readmore, {{WRAPPER}} .msshext-event-readmore' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_readmore_hover',
			[
				'label' => esc_html__( 'Hover', 'msshext' ),
			]
		);

		$this->add_control(
			'readmore_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.msshext-event-readmore:hover, {{WRAPPER}} .msshext-event-readmore:hover, {{WRAPPER}} a.msshext-event-readmore:focus, {{WRAPPER}} .msshext-event-readmore:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} a.msshext-event-readmore:hover svg, {{WRAPPER}} .msshext-event-readmore:hover svg, {{WRAPPER}} a.msshext-event-readmore:focus svg, {{WRAPPER}} .msshext-event-readmore:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'msshext' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'button_text_shadow',
				'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'msshext' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'msshext' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} a.elementor-button:hover svg, {{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} a.elementor-button:focus svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'msshext' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Padding', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	public function get_query() {
		return $this->_query;
	}

	public function query_posts() {

		$settings = $this->get_settings_for_display();

		$query_args = [
			'posts_per_page' => $settings['posts_per_page'],
		];

		/** @var Module_Query $elementor_query */
		$elementor_query = Module_Query::instance();
		$this->_query = $elementor_query->get_query( $this, 'list', $query_args, [] );
	}

	public function render() {

		global $post;
		//$this->query_posts();
		$settings = $this->get_settings_for_display();

		$args = array(
			'post_type'		=> 'msshext_event',
			'posts_per_page'=> $settings['posts_per_page'],
			'meta_query'	=> array(
				'relation' 	=> 'AND',
				'date_start_clause' => array(
					'key' => 'mssh_event_date_start',
					//'value' => 'EXISTS',
				),
				'time_start_clause' => array(
					'key' 		=> 'mssh_event_time_start',
					//'compare' => 'EXISTS',
				),
			),
			'orderby'		=> array(
				'date_start_clause' => 'ASC',
				'time_start_clause' => 'ASC',
			)
		);

		if ( !empty( $settings['timeframe'] ) && $settings['timeframe'] !== 'all' ) {
			$args['meta_query']['date_start_clause']['value'] = wp_date( 'Ymd' );
			if ( $settings['timeframe'] == 'future' ) {
				$args['meta_query']['date_start_clause']['compare'] = '>=';
			}
			if ( $settings['timeframe'] == 'past' ) {
				$args['meta_query']['date_start_clause']['compare'] = '<';
				$args['orderby']['date_start_clause'] = 'DESC';
				$args['orderby']['time_start_clause'] = 'DESC';
			}
		}

		if ( !empty( $settings['terms'] ) ) {
			if ( !is_array( $settings['terms'] ) ) {
				$settings['terms'][] = $settings['terms'];
			}
			$args['tax_query']['relation'] = $settings['terms_relation'];
			foreach ( $settings['terms'] as $term ) {
				$args['tax_query'][$term.'_clause'] = array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $term,
				);
			}
		}

		$posts = get_posts( $args );

		$month = '';
		$counter = 0;
		$show_button = false;
		$this->render_loop_header();

		foreach ( $posts as $post ) {
			setup_postdata( $post );

			if ( $counter == $settings['items_to_show'] ) {
				$show_button = true;
				$this->render_loop_separator();
			}

			if ( !empty( $settings['month_tag'] ) && $settings['group_by_month'] == 'yes' ) {
				$new_month = wp_date( 'F',  strtotime( get_field( 'mssh_event_date_start', $post->ID ) ) );
				if ( $new_month !== $month ) {
					$month = $new_month;
					$this->render_month( $month );
				}
			}

			$this->render_post();
			$counter++;
		}

		if ( empty( $posts ) ) {
			$this->render_no_posts_message();
		}

		$this->render_loop_footer( $show_button );

		wp_reset_postdata();
	}

	protected function render_month( $text ) {

		$settings = $this->get_settings_for_display();

		echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['month_tag'], $this->get_render_attribute_string( 'month_text' ), $text );

	}

	/**
	 * Render image box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_post() {

		$settings = $this->get_settings_for_display();

		$permalink = false;
		$content = get_the_content();
		if ( !empty( $content ) )
			$permalink = get_the_permalink();

		$this->add_render_attribute( 'title_text', 'class', 'elementor-event-title msshext-event-title' );
		//$this->add_inline_editing_attributes( 'title_text', 'none' );

		$this->add_render_attribute( 'excerpt', 'class', 'elementor-event-excerpt msshext-event-excerpt' );
		//$this->add_inline_editing_attributes( 'excerpt', 'none' );

		$html = '<div class="elementor-event-wrapper msshext-event-wrapper">' . PHP_EOL;

		$html.= '<div class="msshext-event-timing msshext-column">' . PHP_EOL;

		$html.= '<span class="msshext-event-date">' . wp_date( $settings['date_format'],  strtotime( get_field( 'mssh_event_date_start', get_the_ID() ) ) ) . '</span>';

		if ( !empty( get_field( 'mssh_event_time_start', get_the_ID() ) ) ) {
			$html.= '<br /><span class="msshext-event-time">' . wp_date( 'G:i',  strtotime( get_field( 'mssh_event_date_start', get_the_ID() ) . ' ' . get_field( 'mssh_event_time_start', get_the_ID() ) ) ) . '</span>';
		}

		$html.= '</div>';

		$html.= '<div class="msshext-event-desc msshext-column">' . PHP_EOL;

		$html.= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_tag'], $this->get_render_attribute_string( 'title_text' ), get_the_title() );

		$html.= sprintf( '<p %s>%s</p>', $this->get_render_attribute_string( 'excerpt' ), get_the_excerpt() );

		if ( $permalink )
			//$html .= '<a href="'.$permalink.'">' . __( 'Zobrazit více', 'msshext' ) . '</a>';
			$html .= $this->get_readmore( $permalink, get_the_title() );

		$html.= '</div>'; //End .msshext-event-desc

		$html .= '</div>'; //End .msshext-event-wrapper

		echo $html;
	}

	protected function render_loop_header() {
		?>
		<div class="elementor-events elementor-grid elementor-posts-container elementor-visible-container msshext-events">
			<div class="msshext-events-visible">
		<?php
	}

	protected function render_loop_footer( $show_button = false ) {
		?>
			</div>
			<?php if ( $show_button ) : ?>
				<?php $this->render_button(); ?>
			<?php endif; ?>
			</div>
		<?php
	}

	protected function render_loop_separator() {
		?>
			</div>
			<div class="msshext-events-hidden">
		<?php
	}

	protected function render_no_posts_message() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( 'no_posts_message', 'class', 'msshext-no-posts-message' );
		echo sprintf( '<span %s>%s</span>', $this->get_render_attribute_string( 'no_posts_message' ), $settings['no_posts_message'] );
	}

	/**
	 * Get button sizes.
	 *
	 * Retrieve an array of button sizes for the button widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array An array containing button sizes.
	 */
	public static function get_button_sizes() {
		return [
			'xs' => esc_html__( 'Extra Small', 'msshext' ),
			'sm' => esc_html__( 'Small', 'msshext' ),
			'md' => esc_html__( 'Medium', 'msshext' ),
			'lg' => esc_html__( 'Large', 'msshext' ),
			'xl' => esc_html__( 'Extra Large', 'msshext' ),
		];
	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_button() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'button_wrapper', 'class', 'elementor-button-wrapper elementor-align-' . $settings['button_align'] );

		if ( ! empty( $settings['button_link']['url'] ) ) {
			$this->add_render_attribute( 'button', 'href', $settings['button_link']['url'] );
			$this->add_render_attribute( 'button', 'class', 'elementor-button-link' );

			if ( $settings['button_link']['is_external'] ) {
				$this->add_render_attribute( 'button', 'target', '_blank' );
			}

			if ( $settings['button_link']['nofollow'] ) {
				$this->add_render_attribute( 'button', 'rel', 'nofollow' );
			}
		}

		$this->add_render_attribute( 'button', 'class', 'elementor-button' );
		$this->add_render_attribute( 'button', 'class', 'msshext-events-show-all' );
		$this->add_render_attribute( 'button', 'role', 'button' );

		if ( ! empty( $settings['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['button_size'] );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

				?>
				<div <?php echo $this->get_render_attribute_string( 'button_wrapper' ); ?>>
					<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
						<?php $this->render_button_text(); ?>
					</a>
				</div>
				<?php
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @since 1.5.0
	 * @access protected
	 */
	protected function render_button_text() {
		$settings = $this->get_settings_for_display();

		$migrated = isset( $settings['__fa4_migrated']['button_selected_icon'] );
		$is_new = empty( $settings['button_icon'] ) && Icons_Manager::is_migration_allowed();

		if ( ! $is_new && empty( $settings['button_icon_align'] ) ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			//old default
			$settings['button_icon_align'] = $this->get_settings_for_display( 'button_icon_align' );
		}

		$this->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'elementor-button-content-wrapper',
			],
			'icon-align' => [
				'class' => [
					'elementor-button-icon',
					'elementor-align-icon-' . $settings['button_icon_align'],
				],
			],
			'text' => [
				'class' => 'elementor-button-text',
			],
		] );

		//$this->add_inline_editing_attributes( 'text', 'none' );
				?>
				<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
					<?php if ( ! empty( $settings['button_icon'] ) || ! empty( $settings['button_selected_icon']['value'] ) ) : ?>
					<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
						<?php if ( $is_new || $migrated ) :
		Icons_Manager::render_icon( $settings['button_selected_icon'], [ 'aria-hidden' => 'true' ] );
		else : ?>
						<i class="<?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></i>
						<?php endif; ?>
					</span>
					<?php endif; ?>
					<span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['button_text']; ?></span>
				</span>
				<?php
	}

	/**
	 * Render readmore output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_readmore( $href = '#', $label = '' ) {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'readmore_wrapper', 'class', 'elementor-button-wrapper elementor-align-' . $settings['button_align'] );

		if ( ! empty( $href ) ) {
			$this->add_render_attribute( 'readmore', 'href', $href );
			$this->add_render_attribute( 'readmore', 'class', 'elementor-button-link' );
		}

		$this->add_render_attribute( 'readmore', 'class', 'elementor-button' );
		$this->add_render_attribute( 'readmore', 'class', 'msshext-event-readmore' );
		$this->add_render_attribute( 'readmore', 'role', 'link' );

		ob_start();

				?>
				<div <?php echo $this->get_render_attribute_string( 'readmore_wrapper' ); ?>>
					<a <?php echo $this->get_render_attribute_string( 'readmore' ); ?> aria-label="<?php echo $settings['readmore_text'] . ' - ' . $label; ?>">
						<?php $this->render_readmore_text(); ?>
					</a>
				</div>
				<?php

		return ob_get_clean();
	}

	/**
	 * Render readmore text.
	 *
	 * @since 1.5.0
	 * @access protected
	 */
	protected function render_readmore_text() {
		$settings = $this->get_settings_for_display();

		$migrated = isset( $settings['__fa4_migrated']['readmore_selected_icon'] );
		$is_new = empty( $settings['readmore_icon'] ) && Icons_Manager::is_migration_allowed();

		if ( ! $is_new && empty( $settings['readmore_icon_align'] ) ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			//old default
			$settings['readmore_icon_align'] = $this->get_settings_for_display( 'readmore_icon_align' );
		}

		$this->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'elementor-button-content-wrapper',
			],
			'icon-align' => [
				'class' => [
					'elementor-button-icon',
					'elementor-align-icon-' . $settings['readmore_icon_align'],
				],
			],
			'text' => [
				'class' => 'elementor-button-text',
			],
		] );

		//$this->add_inline_editing_attributes( 'text', 'none' );
				?>
				<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
					<?php if ( ! empty( $settings['readmore_icon'] ) || ! empty( $settings['readmore_selected_icon']['value'] ) ) : ?>
					<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
						<?php if ( $is_new || $migrated ) :
		Icons_Manager::render_icon( $settings['readmore_selected_icon'], [ 'aria-hidden' => 'true' ] );
		else : ?>
						<i class="<?php echo esc_attr( $settings['readmore_icon'] ); ?>" aria-hidden="true"></i>
						<?php endif; ?>
					</span>
					<?php endif; ?>
					<span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['readmore_text']; ?></span>
				</span>
				<?php
	}

	/**
	 * Render image box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
?>
<#
   var html = '<div class="elementor-event-wrapper msshext-advanced-event-wrapper">';

   var hasContent = !! ( settings.title_text || settings.description_text );

   if ( hasContent ) {
   html += '<div class="elementor-event-content msshext-advanced-event-content">';

   if ( settings.title_text ) {
   var title_html = settings.title_text;

   view.addRenderAttribute( 'title_text', 'class', 'elementor-event-title msshext-advanced-event-title' );

   view.addInlineEditingAttributes( 'title_text', 'none' );

   html += '<' + settings.title_tag  + ' ' + view.getRenderAttributeString( 'title_text' ) + '>' + title_html + '</' + settings.title_tag  + '>';
   }

   if ( settings.description_text ) {
   view.addRenderAttribute( 'description_text', 'class', 'elementor-event-description msshext-advanced-event-description' );

   view.addInlineEditingAttributes( 'description_text' );

   html += '<p ' + view.getRenderAttributeString( 'description_text' ) + '>' + settings.description_text + '</p>';
   }

   html += '</div>';
   }

   html += '</div>';

   print( html );
   #>
	<?php
	}

}
