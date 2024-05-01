<?php

defined( 'ABSPATH' ) || exit;

class Hostinger_Default_Options {
	public function add_options(): void {
		foreach ( $this->options() as $key => $option ) {
			update_option( $key, $option );
		}
	}

	private function options(): array {
		$options = array(
			'optin_monster_api_activation_redirect_disabled' => 'true',
			'wpforms_activation_redirect' => 'true',
			'aioseo_activation_redirect'  => 'false',
		);

		if ( Hostinger_Helper::is_plugin_active( 'astra-sites' ) ) {
			$options = array_merge( $options, $this->get_astra_options() );
		}

		return $options;
	}

	private function get_astra_options(): array {
		return array(
			'astra_sites_settings'      => 'gutenberg',
			'st-elementor-builder-flag' => '1',
		);
	}
}
