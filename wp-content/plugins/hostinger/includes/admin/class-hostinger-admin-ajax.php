<?php

defined( 'ABSPATH' ) || exit;

class Hostinger_Admin_Ajax {
	private const PROMOTIONAL_BANNER_TRANSIENT = 'hts_hide_promotional_banner_transient';
	private const HIDE_SURVEY_TRANSIENT        = 'hts_hide_survey';
	private const HIDE_OMNISEND_NOTICE         = 'hts_omnisend_notice_hidden';
	private const TWO_DAYS                     = 86400 * 2;
	private const THIRTY_DAYS                  = 86400 * 30;

	private Hostinger_Config $config_handler;
	private Hostinger_Settings $settings;
	private Hostinger_Helper $helper;
	private Hostinger_Surveys_Questions $survey_questions;
	private Hostinger_Surveys_Rest $surveys_rest;

	public function __construct() {
		$this->settings       = new Hostinger_Settings();
		$this->helper         = new Hostinger_Helper();
		$this->config_handler = new Hostinger_Config();

		if ( ! empty( Hostinger_Helper::get_api_token() ) ) {
			$this->survey_questions = new Hostinger_Surveys_Questions();
			$client                 = new Hostinger_Requests_Client(
				$this->config_handler->get_config_value( 'base_rest_uri', HOSTINGER_REST_URI ),
				array(
					Hostinger_Config::TOKEN_HEADER  => $this->helper::get_api_token(),
					Hostinger_Config::DOMAIN_HEADER => $this->helper->get_host_info(),
				)
			);

			$this->surveys_rest = new Hostinger_Surveys_Rest( $client );
		}

		add_action( 'init', array( $this, 'define_ajax_events' ), 0 );
	}

	public function define_ajax_events(): void {
		$events = array(
			'complete_onboarding_step',
			'publish_website',
			'identify_action',
			'menu_action',
			'woocommerce_setup_store',
			'hide_promotional_banner',
			'get_survey',
			'submit_survey',
			'hide_survey',
			'dismiss_omnisend_notice',
		);

		foreach ( $events as $event ) {
			add_action( 'wp_ajax_hostinger_' . $event, array( $this, $event ) );
		}

		if ( Hostinger_Helper::is_woocommerce_site() ) {
			add_action( 'wp_ajax_hostinger_woo_onboarding_choice', array( $this, 'woo_onboarding_choice' ) );
		}
	}

	public function get_survey(): void {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		$type  = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : 'ai_survey';
		$security_check = $this->request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		if ( get_transient( self::HIDE_SURVEY_TRANSIENT ) ) {
			wp_send_json_error( 'Survey hidden' );
		}

		$surveys = new Hostinger_Surveys( $this->settings, $this->helper, $this->config_handler, $this->survey_questions, $this->surveys_rest );

		$survey_questions = $surveys->get_wp_survey_questions();
		$questions_json   = $surveys->get_specified_survey_questions( $survey_questions, $type );

		wp_send_json( $questions_json );
	}

	public function submit_survey(): void {
		$nonce          = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		$survey_type    = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
		$survey_results = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['survey_results'] ) : '';
		$surveys        = new Hostinger_Surveys( $this->settings, $this->helper, $this->config_handler, $this->survey_questions, $this->surveys_rest );

		$security_check = $this->request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		$decoded_json = json_decode( stripslashes( $survey_results ), true );
		$surveys->submit_survey_answers( $decoded_json, $survey_type );
	}

	public function hide_survey(): void {
		$nonce          = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		$transient_key  = self::HIDE_SURVEY_TRANSIENT;
		$security_check = $this->request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		if ( false === get_transient( $transient_key ) ) {
			set_transient( $transient_key, time(), self::THIRTY_DAYS );
		}

		wp_send_json_success( array() );
	}

	public function hide_promotional_banner(): void {
		$nonce          = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		$transient_key  = self::PROMOTIONAL_BANNER_TRANSIENT;
		$security_check = $this->request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		if ( false === get_transient( $transient_key ) ) {
			set_transient( $transient_key, time(), self::TWO_DAYS );
		}

		wp_send_json_success( array() );
	}

	public function woocommerce_setup_store(): void {
		$nonce        = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		$event_action = isset( $_POST['event_action'] ) ? sanitize_text_field( $_POST['event_action'] ) : '';

		$security_check = $this->request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		$amplitude = new Hostinger_Amplitude();
		$amplitude->setup_store( $event_action );

		wp_send_json_success( array() );
	}

	public function publish_website(): void {
		$publish        = (bool) $_POST['maintenance'];
		$nonce          = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		$security_check = $this->request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		Hostinger_Settings::update_setting( 'maintenance_mode', $publish ? 1 : 0 );

		require_once HOSTINGER_ABSPATH . 'includes/admin/onboarding/class-hostinger-onboarding.php';
		$content = new Hostinger_Onboarding();

		if ( has_action( 'litespeed_purge_all' ) ) {
			do_action( 'litespeed_purge_all' );
		}

		wp_send_json_success(
			array(
				'published'   => $publish,
				'title'       => __( 'Website is published', 'hostinger' ),
				'description' => __( 'Congratulations! Your website is online.', 'hostinger' ),
				'content'     => $content->get_content(),
				'preview_url' => home_url(),
			)
		);
	}

	public function complete_onboarding_step(): void {
		$step  = isset( $_POST['step'] ) ? sanitize_text_field( $_POST['step'] ) : '';
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

		$security_check = $this->request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		$completed_steps = get_option( 'hostinger_onboarding_steps', array() );
		if ( ! in_array( $step, array_column( $completed_steps, 'action' ), true ) ) {
			$completed_steps[] = array(
				'action' => $step,
				'date'   => gmdate( 'Y-m-d H:i:s' ),
			);
		}
		Hostinger_Settings::update_setting( 'onboarding_steps', $completed_steps );

		wp_send_json_success( array() );
	}

	public function identify_action(): void {
		$action = sanitize_text_field( $_POST['action_name'] ) ?? '';

		if ( in_array( $action, Hostinger_Admin_Actions::ACTIONS_LIST, true ) ) {
			setcookie( $action, $action, time() + ( 86400 ), '/' );
			wp_send_json_success( $action );
		} else {
			wp_send_json_error( 'Invalid action' );
		}
	}

	public function menu_action(): void {
		$nonce        = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		$location     = sanitize_text_field( $_POST['location'] ) ?? '';
		$event_action = sanitize_text_field( $_POST['event_action'] ) ?? '';

		$security_check = $this->request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		$amplitude = new Hostinger_Amplitude();
		$amplitude->track_menu_action( $event_action, $location );

		wp_send_json_success( array() );
	}

	public function request_security_check( $nonce ) {
		if ( ! wp_verify_nonce( $nonce, 'hts-ajax-nonce' ) ) {
			return 'Invalid nonce';
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return 'Lack of permissions';
		}

		return false;
	}

	public function woo_onboarding_choice(): void {
		$nonce          = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		$choice         = isset( $_POST['choice'] ) ? sanitize_text_field( $_POST['choice'] ) : '';
		$security_check = $this->request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		$allowed_choices = array(
			'woocommerce',
			'hostinger',
		);

		if ( ! in_array( $choice, $allowed_choices, true ) || empty( $choice ) ) {
			wp_send_json_error( __( 'Something went wrong. Try again.', 'hostinger' ) );
		}

		$base_url = 'https://' . $this->helper->get_host_info() . '/';

		$redirect_url = '';

		switch ( $choice ) {
			case 'woocommerce':
				$redirect_url = $base_url . 'wp-admin/admin.php?page=wc-admin&path=' . rawurlencode( '/setup-wizard' );

				add_option( 'hostinger_woo_ready_message_shown', 0 );

				break;
			case 'hostinger':
				$redirect_url = $base_url . 'wp-admin/admin.php?page=hostinger';
				break;
		}

		update_option( 'hostinger_woo_onboarding_choice_value', $choice, false );
		update_option( 'hostinger_woo_onboarding_choice', true, false );

		if ( has_action( 'litespeed_purge_all' ) ) {
			do_action( 'litespeed_purge_all' );
		}

		$response = array(
			'redirect_url' => $redirect_url,
		);

		wp_send_json_success( $response );
	}

	public function dismiss_omnisend_notice(): void {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'hts_close_omnisend' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		$_SESSION[ self::HIDE_OMNISEND_NOTICE ] = true;
		wp_die();
	}
}

new Hostinger_Admin_Ajax();
