<?php
namespace MSSHEXT\Elementor\Modules\Menus\Widgets;

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
//use ElementorPro\Modules\QueryControl\Module as Module_Query;
//use ElementorPro\Modules\QueryControl\Controls\Group_Control_Related;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * @since 1.0.0
 */
class Menus extends Widget_Base {

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
		return 'msshext-menus';
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
		return __( 'MŠ Jídelníček', 'msshext' );
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
	 * Register menu widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		/**
		 * Content - MENUS
		 */

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Karty', 'msshext' ),
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Počet položek', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => 5,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Sloupce', 'msshext' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'prefix_class' => 'elementor-grid%s-',
				'frontend_available' => true,
				'selectors' => [
					'.elementor-msie {{WRAPPER}} .msshext-menu-item' => 'width: calc( 100% / {{SIZE}} )',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Zarovnání obsahu', 'msshext' ),
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
				'label' => esc_html__( 'Formát data', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'l j. n.',
			]
		);

		$this->add_control(
			'date_tag',
			[
				'label' => esc_html__( 'HTML tag data', 'msshext' ),
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
			'food_title_tag',
			[
				'label' => esc_html__( 'HTML Tag nadpisu položky', 'msshext' ),
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

		$this->add_control(
			'no_posts_message',
			[
				'label' => esc_html__( 'No posts found message', 'msshext' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Zatím žádné události.', 'msshext' ),
			]
		);

		$this->add_control(
			'show_general_info',
			[
				'label' => esc_html__( 'Zobrazit obecné informace', 'msshext' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->end_controls_section();

		/**
		 * CSS - MENUS
		 */

		$this->start_controls_section(
			'section_style_menu_card',
			[
				'label' => esc_html__( 'Karta', 'msshext' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'menu_card_style',
			[
				'label' => esc_html__( 'Vzhled karty', 'msshext' ),
				'type' => Controls_Manager::HEADING,
				'separator' => false,
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label' => esc_html__( 'Columns Gap', 'msshext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-posts-container' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
					'.elementor-msie {{WRAPPER}} .elementor-portfolio' => 'margin: 0 -{{SIZE}}px',
					'.elementor-msie {{WRAPPER}} .elementor-portfolio-item' => 'border-style: solid; border-color: transparent; border-right-width: calc({{SIZE}}px / 2); border-left-width: calc({{SIZE}}px / 2)',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'msshext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-posts-container' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
					'.elementor-msie {{WRAPPER}} .elementor-portfolio-item' => 'border-bottom-width: {{SIZE}}px',
				],
			]
		);

		$this->add_control(
			'menu_card_background_color',
			[
				'label' => esc_html__( 'Background Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'menu_card_border',
				'selector' => '{{WRAPPER}} .msshext-menu-item',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'menu_card_radius',
			[
				'label' => esc_html__( 'Border Radius', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'menu_card_margin',
			[
				'label' => esc_html__( 'Card Margin', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_card_padding',
			[
				'label' => esc_html__( 'Padding', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'menu_card_box_shadow',
				'selector' => '{{WRAPPER}} .msshext-menu-item',
			]
		);

		$this->add_control(
			'menu_card_date',
			[
				'label' => esc_html__( 'Vzhled data', 'msshext' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => esc_html__( 'Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-date' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .msshext-menu-date',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'date_margin',
			[
				'label' => esc_html__( 'Margin', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * CSS - MENU INNER
		 */

		$this->start_controls_section(
			'section_style_menu_inner',
			[
				'label' => esc_html__( 'Vnitřek karty', 'msshext' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'menu_inner_background_color',
			[
				'label' => esc_html__( 'Background Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-content-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'menu_inner_border',
				'selector' => '{{WRAPPER}} .msshext-menu-content-wrapper',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'menu_inner_radius',
			[
				'label' => esc_html__( 'Border Radius', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'menu_inner_margin',
			[
				'label' => esc_html__( 'Card Margin', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_inner_padding',
			[
				'label' => esc_html__( 'Padding', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'menu_inner_box_shadow',
				'selector' => '{{WRAPPER}} .msshext-menu-content-wrapper',
			]
		);

		$this->add_control(
			'menu_inner_headings',
			[
				'label' => esc_html__( 'Vzhled nadpisů', 'msshext' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'food_title_color',
			[
				'label' => esc_html__( 'Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-content-wrapper .msshext-menu-food-type' => 'color: {{VALUE}};',
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
				'name' => 'food_title_typography',
				'selector' => '{{WRAPPER}} .msshext-menu-content-wrapper .msshext-menu-food-type',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'food_title_margin',
			[
				'label' => esc_html__( 'Margin', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-content-wrapper .msshext-menu-food-type' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'menu_inner_foods',
			[
				'label' => esc_html__( 'Vzhled jídel', 'msshext' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'food_content_color',
			[
				'label' => esc_html__( 'Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-content-wrapper .msshext-menu-food-list' => 'color: {{VALUE}};',
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
				'name' => 'food_content_typography',
				'selector' => '{{WRAPPER}} .msshext-menu-content-wrapper .msshext-menu-food-list',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'food_content_margin',
			[
				'label' => esc_html__( 'Margin', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-content-wrapper .msshext-menu-food-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'menu_inner_allergens',
			[
				'label' => esc_html__( 'Vzhled alergenů', 'msshext' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'food_allergens_color',
			[
				'label' => esc_html__( 'Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-content-wrapper .msshext-menu-food-allergens' => 'color: {{VALUE}};',
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
				'name' => 'food_allergens_typography',
				'selector' => '{{WRAPPER}} .msshext-menu-content-wrapper .msshext-menu-food-allergens',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'food_allergens_margin',
			[
				'label' => esc_html__( 'Margin', 'msshext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-content-wrapper .msshext-menu-food-allergens' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * CSS - MENU INFO
		 */

		$this->start_controls_section(
			'section_style_menu_info',
			[
				'label' => esc_html__( 'Infokarta', 'msshext' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'infocard_background_color',
			[
				'label' => esc_html__( 'Background Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-info-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'infocard_border',
				'selector' => '{{WRAPPER}} .msshext-menu-info-item',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'infocard_date',
			[
				'label' => esc_html__( 'Vzhled nadpisu', 'msshext' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'infocard_heading_color',
			[
				'label' => esc_html__( 'Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-info-item .msshext-menu-date' => 'color: {{VALUE}};',
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
				'name' => 'infocard_heading_typography',
				'selector' => '{{WRAPPER}} .msshext-menu-info-item .msshext-menu-date',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'infocard_inner_background_color',
			[
				'label' => esc_html__( 'Background Color', 'msshext' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .msshext-menu-info-item .msshext-menu-content-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'infocard_inner_border',
				'selector' => '{{WRAPPER}} .msshext-menu-info-item .msshext-menu-content-wrapper',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		global $post;

		$settings = $this->get_settings_for_display();

		$args = array(
			'post_type'		=> 'msshext_daily_menu',
			'posts_per_page'=> $settings['posts_per_page'],
			'meta_query'	=> array(
				'relation' 	=> 'AND',
				'date_clause'	=> array(
					'key'		=> 'msshext_daily_menu_date',
					'compare'	=> '>=',
					'value'		=> wp_date( 'Ymd' ),
				),
			),
			'orderby'		=> array(
				'date_clause' 	=> 'ASC',
			)
		);

		$posts = get_posts( $args );

		$month = '';
		$counter = 0;
		$show_button = false;
		$this->render_loop_header();

		foreach ( $posts as $post ) {
			setup_postdata( $post );

			$this->render_post();
			$counter++;
		}

		if ( $settings['show_general_info'] == 'yes' )
			$this->render_info_card();

		if ( empty( $posts ) ) {
			$this->render_no_posts_message();
		}

		$this->render_loop_footer( $show_button );

		wp_reset_postdata();
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

		$this->add_render_attribute( 'date', 'class', 'msshext-menu-date' );
		$this->add_render_attribute( 'food-type', 'class', 'msshext-menu-food-type' );
		$this->add_render_attribute( 'food-type', 'class', 'msshext-menu-food-line' );
		$this->add_render_attribute( 'food-list', 'class', 'msshext-menu-food-list' );
		$this->add_render_attribute( 'food-list', 'class', 'msshext-menu-food-line' );
		$this->add_render_attribute( 'food-allergens', 'class', 'msshext-menu-food-allergens' );

		$html = '<article class="msshext-menu-item elementor-post elementor-grid-item">' . PHP_EOL;

		$html.= '<div class="msshext-menu-date-wrapper">' . PHP_EOL;

		$html.= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['date_tag'], $this->get_render_attribute_string( 'date' ),
						$this->get_formatted_date( get_field( 'msshext_daily_menu_date', get_the_ID() ), $settings['date_format'] )
				);

		$html.= '</div>';

		$html.= '<div class="msshext-menu-content-wrapper">' . PHP_EOL;

		$html.= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['food_title_tag'], $this->get_render_attribute_string( 'food-type' ), __( 'Přesnídávka', 'msshext' ) );
		$html.= '<p ' . $this->get_render_attribute_string( 'food-list' ) . '>' . get_field( 'msshext_daily_menu_snack_1', get_the_ID() ) . '</p>';
		$html.= '<p ' . $this->get_render_attribute_string( 'food-allergens' ) . '>' . __( 'alergeny:', 'msshext' ) . ' ' . get_field( 'msshext_daily_menu_snack_1_allergens', get_the_ID() ) . '</p>';

		$html.= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['food_title_tag'], $this->get_render_attribute_string( 'food-type' ), __( 'Polévka', 'msshext' ) );
		$html.= '<p ' . $this->get_render_attribute_string( 'food-list' ) . '>' . get_field( 'msshext_daily_menu_soup', get_the_ID() ) . '</p>';
		$html.= '<p ' . $this->get_render_attribute_string( 'food-allergens' ) . '>' . __( 'alergeny:', 'msshext' ) . ' ' . get_field( 'msshext_daily_menu_soup_allergens', get_the_ID() ) . '</p>';

		$html.= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['food_title_tag'], $this->get_render_attribute_string( 'food-type' ), __( 'Hlavní jídlo', 'msshext' ) );
		$html.= '<p ' . $this->get_render_attribute_string( 'food-list' ) . '>' . get_field( 'msshext_daily_menu_lunch', get_the_ID() ) . '</p>';
		$html.= '<p ' . $this->get_render_attribute_string( 'food-allergens' ) . '>' . __( 'alergeny:', 'msshext' ) . ' ' . get_field( 'msshext_daily_menu_lunch_allergens', get_the_ID() ) . '</p>';

		$html.= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['food_title_tag'], $this->get_render_attribute_string( 'food-type' ), __( 'Svačina', 'msshext' ) );
		$html.= '<p ' . $this->get_render_attribute_string( 'food-list' ) . '>' . get_field( 'msshext_daily_menu_snack_2', get_the_ID() ) . '</p>';
		$html.= '<p ' . $this->get_render_attribute_string( 'food-allergens' ) . '>' . __( 'alergeny:', 'msshext' ) . ' ' . get_field( 'msshext_daily_menu_snack_2_allergens', get_the_ID() ) . '</p>';

		$html.= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['food_title_tag'], $this->get_render_attribute_string( 'food-type' ), __( 'Pitný režim', 'msshext' ) );
		$html.= '<p ' . $this->get_render_attribute_string( 'food-list' ) . '>' . get_field( 'msshext_daily_menu_drinks', get_the_ID() ) . '</p>';

		$html.= '</div>'; //End .msshext-menu-desc

		$html .= '</article>'; //End .msshext-menu-item

		echo $html;
	}

	protected function render_info_card() {

		$settings = $this->get_settings_for_display();

		$heading = get_field( 'msshext_daily_menu_general_info_heading', 'options' );
		$content = get_field( 'msshext_daily_menu_general_info_content', 'options' );

		// make sure there is info content to show.
		if ( empty( $content ) )
			return;

		$this->add_render_attribute( 'info-heading', 'class', 'msshext-menu-date' );
		$this->add_render_attribute( 'info-content', 'class', 'msshext-menu-info-content' );

		$html = '<article class="msshext-menu-item msshext-menu-info-item elementor-post elementor-grid-item">' . PHP_EOL;

		$html.= '<div class="msshext-menu-date-wrapper">' . PHP_EOL;

		$html.= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['date_tag'], $this->get_render_attribute_string( 'info-heading' ), $heading );

		$html.= '</div>';

		$html.= '<div class="msshext-menu-content-wrapper">' . PHP_EOL;

		$html.= '<div ' . $this->get_render_attribute_string( 'info-content' ) . '>' . $content . '</div>';

		$html.= '</div>'; //End .msshext-menu-desc

		$html .= '</article>'; //End .msshext-menu-item

		echo $html;
	}

	protected function render_loop_header() {
		?>
		<div class="elementor-grid elementor-posts-container elementor-visible-container msshext-menus">
		<?php
	}

	protected function render_loop_footer( $show_button = false ) {
		?>
		</div>
		<?php
	}

	protected function render_no_posts_message() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( 'no_posts_message', 'class', 'msshext-no-posts-message' );
		echo sprintf( '<span %s>%s</span>', $this->get_render_attribute_string( 'no_posts_message' ), $settings['no_posts_message'] );
	}

	public function get_formatted_date( $date, $new_format ) {
		return msshext_get_formatted_date( $date, $new_format );
	}

}
