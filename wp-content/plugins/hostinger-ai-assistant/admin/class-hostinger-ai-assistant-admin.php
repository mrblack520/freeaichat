<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://hostinger.com
 * @since      1.0.0
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/admin
 * @author     Hostinger <info@hostinger.com>
 */
class Hostinger_Ai_Assistant_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private string $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private string $version;
	private array $translations;
	private array $localization_params;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( string $plugin_name, string $version ) {
		$this->plugin_name         = $plugin_name;
		$this->version             = $version;
		$translations              = new Hostinger_Frontend_Translations();
		$this->translations        = $translations->get_frontend_translations();
		$this->localization_params = array_merge( $this->translations, array(
			'tabUrl' => admin_url() . 'admin.php?page=hostinger#ai-assistant',
		) );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles(): void {

		wp_enqueue_style( $this->plugin_name, HOSTINGER_AI_ASSISTANT_ASSETS_URL . '/css/hostinger-ai-assistant-admin.css', array(), $this->version, 'all' );

		if( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style( 'hostinger_ai_assistant_woo_styles', HOSTINGER_AI_ASSISTANT_ASSETS_URL . '/css/woo-styles.css', array(), $this->version, 'all');
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts(): void {
		$helper = new Hostinger_Ai_Assistant_Helper();
		if ( Hostinger_Ai_Assistant_Helper::is_plugin_active( 'hostinger' ) && $helper->is_hostinger_admin_page() ) {
			wp_enqueue_script( $this->plugin_name, HOSTINGER_AI_ASSISTANT_ASSETS_URL . '/js/hostinger-ai-assistant-admin.js', array(
				'jquery',
				'wp-i18n'
			), $this->version, false );
			wp_localize_script( $this->plugin_name, 'hostingerAiAssistant', $this->localization_params );
		}

		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_script( 'hostinger_ai_assistant_woo_requests', HOSTINGER_AI_ASSISTANT_ASSETS_URL . '/js/hostinger-woo-requests.js', array(
				'jquery',
				'wp-i18n'
			), $this->version, false );
		}

	}

	public function enqueue_custom_editor_assets(): void {
		wp_enqueue_script( 'custom-link-in-toolbar', HOSTINGER_AI_ASSISTANT_ASSETS_URL . '/js/hostinger-buttons.js', array(
			'jquery',
			'wp-blocks',
			'wp-dom',
			'wp-i18n'
		), $this->version, false );
		wp_set_script_translations( 'custom-link-in-toolbar', 'hostinger-ai-assistant' );
		wp_localize_script( 'custom-link-in-toolbar', 'hostingerAiAssistant', $this->localization_params );
	}

	/**
	 * Add AI Assistant view
	 *
	 * @since    1.0.0
	 */
	public function create_ai_assistant_tab_view(): void {
		include_once HOSTINGER_AI_ASSISTANT_ABSPATH . 'admin/partials/hostinger-ai-assistant-tab-view.php';
	}

}
