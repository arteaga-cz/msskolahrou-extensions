<?php
namespace MSSHEXT\Elementor\Modules\Events\Widgets;

use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as Module_Query;
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Related;

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

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [ '' => '' ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
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
	protected function _register_controls() {
		$this->start_controls_section(
			'section_events',
			[
				'label' => __( 'Events', 'msshext' ),
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Items to show', 'wpupee' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => 6,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'wpupee' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'tablet_default' => '1',
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
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'elementor' ),
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
			'group_by_month',
			[
				'label' => __( 'Seskupit podle měsíce', 'msshext' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'month_tag',
			[
				'label' => __( 'Month HTML Tag', 'elementor' ),
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
				'default' => 'h ',
				'condition' => [
					'group_by_month' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-event-content .elementor-event-title' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .elementor-event-content .elementor-event-title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label' => __( 'Description', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-event-content .elementor-event-description' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-event-content .elementor-event-description *' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .elementor-event-content .elementor-event-description',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Related::get_type(),
			[
				'name' => 'posts',
				'presets' => [ 'full' ],
				'exclude' => [
					'posts_per_page', //use the one from Layout section
				],
			]
		);

		$this->end_controls_section();
	}

	public function get_query() {
		return $this->_query;
	}

	public function query_posts() {

		$query_args = [
			'posts_per_page' => $this->get_settings( 'posts_per_page' ),
		];

		/** @var Module_Query $elementor_query */
		$elementor_query = Module_Query::instance();
		$this->_query = $elementor_query->get_query( $this, 'list', $query_args, [] );
	}

	public function render() {

		global $post;
		//$this->query_posts();
		$settings = $this->get_settings_for_display();

		$posts = get_posts( array(
			'post_type'		=> 'msshext_event',
			'posts_per_page'=> $this->get_settings( 'posts_per_page' ),
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
		) );

		if ( empty( $posts ) ) {
			return;
		}

		$month = '';
		$this->render_loop_header();
		foreach ( $posts as $post ) {
			setup_postdata( $post );

			if ( !empty( $settings['month_tag'] ) && $settings['group_by_month'] == 'yes' ) {
				$new_month = date_i18n( 'F',  strtotime( get_field( 'mssh_event_date_start', $post->ID ) ) );
				if ( $new_month !== $month ) {
					$month = $new_month;
					$this->render_month( $month );
				}
			}

			$this->render_post();
		}
		$this->render_loop_footer();

		wp_reset_postdata();
	}

	protected function render_month( $text ) {

		$settings = $this->get_settings_for_display();

		$html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['month_tag'], $this->get_render_attribute_string( 'month_text' ), $text );

		error_log( '$html: ' . $html );

		echo $html;

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
		$this->add_inline_editing_attributes( 'title_text', 'none' );

		$html = '<div class="elementor-event-wrapper msshext-event-wrapper">' . PHP_EOL;

		$html.= '<div class="msshext-event-timing">' . PHP_EOL;

		$html.= get_field( 'mssh_event_date_start', get_the_ID() );

		$html.= '</div>';

		$html.= '<div class="msshext-event-desc">' . PHP_EOL;

		$html.= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_tag'], $this->get_render_attribute_string( 'title_text' ), get_the_title() );

		$html.= apply_filters( 'the_content', get_the_excerpt() );

		if ( $permalink )
			$html .= '<a href="'.$permalink.'">' . __( 'Zobrazit více', 'msshext' ) . '</a>';

		$html.= '</div>';

		/*if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'link', 'href', $settings['link']['url'] );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}
		}

		if ( $has_content ) {
			$html .= '<div class="elementor-event-content msshext-advanced-event-content">';

			if ( ! empty( $settings['title_text'] ) ) {
				$this->add_render_attribute( 'title_text', 'class', 'elementor-event-title msshext-advanced-event-title' );

				$this->add_inline_editing_attributes( 'title_text', 'none' );

				$title_html = $settings['title_text'];

				if ( ! empty( $settings['link']['url'] ) ) {
					$title_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $title_html . '</a>';
				}

				$html .= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_tag'], $this->get_render_attribute_string( 'title_text' ), $title_html );
			}

			if ( ! empty( $settings['description_text'] ) ) {
				$this->add_render_attribute( 'description_text', 'class', 'elementor-event-description msshext-advanced-event-description' );

				$this->add_inline_editing_attributes( 'description_text' );

				$html .= sprintf( '<div %1$s>%2$s</div>', $this->get_render_attribute_string( 'description_text' ), $settings['description_text'] );
			}

			$html .= '</div>';
		}*/

		$html .= '</div>';

		echo $html;
	}

	protected function render_loop_header() {
		?>
		<div class="elementor-events elementor-grid elementor-posts-container msshext-events">
		<?php
	}

	protected function render_loop_footer() {
		?>
		</div>
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
	protected function _content_template2() {
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
