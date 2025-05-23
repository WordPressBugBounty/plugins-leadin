<?php
namespace Leadin\admin\widgets;

use Leadin\data\Filters;
use Leadin\data\Portal_Options;
use Elementor\Plugin;
use Elementor\Widget_Base;

/**
 * ElementorForm Widget
 */
class ElementorForm extends Widget_Base {

	/**
	 * Widget internal name.
	 */
	public function get_name() {
		return 'hubspot-form';
	}

	/**
	 * Widget title.
	 */
	public function get_title() {
		return esc_html( 'HubSpot Form' );
	}

	/**
	 * Widget display icon.
	 */
	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	/**
	 * Widget help url.
	 */
	public function get_custom_help_url() {
		return 'https://wordpress.org/support/plugin/leadin/';
	}

	/**
	 * Widget category.
	 */
	public function get_categories() {
		return array( 'general', 'hubspot' );
	}

	/**
	 * Widget keywords.
	 */
	public function get_keywords() {
		return array( 'hubspot', 'form', 'leadin' );
	}

	/**
	 * Widget style.
	 */
	public function get_style_depends() {
		wp_register_style( 'leadin-elementor', LEADIN_JS_BASE_PATH . '/elementor.css', array(), LEADIN_PLUGIN_VERSION );
		wp_register_style( 'leadin-css', LEADIN_ASSETS_PATH . '/style/leadin.css', array(), LEADIN_PLUGIN_VERSION );
		return array( 'leadin-elementor', 'leadin-css' );
	}

	/**
	 * Widget script.
	 */
	public function get_script_depends() {
		wp_register_script(
			'leadin-forms-v2',
			Filters::apply_forms_script_url_filters(),
			array(),
			LEADIN_PLUGIN_VERSION,
			true
		);

		$scopes    = array( 'leadin-forms-v2' );
		$portal_id = Portal_Options::get_portal_id();
		if ( $portal_id ) {
			wp_register_script(
				'leadin-forms-v4',
				Filters::apply_forms_v4_script_url_filters( $portal_id ),
				array(),
				LEADIN_PLUGIN_VERSION,
				true
			);
			$scopes[] = 'leadin-forms-v4';
		}
		return $scopes;
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html( __( 'Form', 'leadin' ) ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'content',
			array(
				'type' => 'leadinformselect',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$content  = $settings['content'];

		if ( Plugin::$instance->editor->is_edit_mode() ) {

			?>
				<div class="hubspot-form-edit-mode" data-attributes="<?php echo esc_attr( wp_json_encode( $content ) ); ?>">
				&nbsp;
				</div>
				<?php
				if ( empty( $content ) ) {
					?>
							<div class="hubspot-widget-empty">

							</div>
					<?php
				}
		}

		if ( ! empty( $content ) ) {
				$portal_id = $content['portalId'];
				$form_id   = $content['formId'];
				$version   = isset( $content['embedVersion'] ) ? $content['embedVersion'] : '';
				echo do_shortcode( '[hubspot portal="' . $portal_id . '" id="' . $form_id . '" type="form" version="' . $version . '"]' );
		}
	}
}
