<?php

defined( 'ABSPATH' ) || exit;

class Hostinger_Activator {

	public static function activate(): void {
		require_once HOSTINGER_ABSPATH . 'includes/class-hostinger-default-options.php';
		$options = new Hostinger_Default_Options();
		$options->add_options();

		self::update_installation_state_on_activation();
	}

	/**
	 * Saves installation state.
	 */
	public static function update_installation_state_on_activation(): void {
		$installation_state = get_option( 'hts_new_installation', false );

		if ( $installation_state !== 'old' ) {
			add_option( 'hts_new_installation', 'new' );
		}
	}
}
