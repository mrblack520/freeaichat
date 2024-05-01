<?php

defined( 'ABSPATH' ) || exit;

class Hostinger_Admin_Hooks {

	private Hostinger_Helper $helper;

	public function __construct() {
		$this->helper = new Hostinger_Helper();
		add_action( 'admin_footer', array( $this, 'rate_plugin' ) );
		add_action( 'admin_init', array( $this, 'hide_astra_builder_selection_screen' ) );
		add_action( 'admin_init', array( $this, 'enable_woo_onboarding' ) );
		add_action( 'admin_notices', array( $this, 'omnisend_discount_notice' ) );
		add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_true' );

		if ( ! Hostinger_Helper::show_woocommerce_onboarding() ) {
			add_filter( 'admin_body_class', array( $this, 'add_woocommerce_onboarding_class' ) );
		}

		add_action( 'admin_init', array( $this, 'store_ready_message_logic' ), 0 );
		add_action( 'admin_notices', array( $this, 'show_store_ready_message' ), 0 );
		add_action( 'admin_head', array( $this, 'force_woo_notices' ) );
	}

	public function enable_woo_onboarding(): bool {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}

		if ( ! $this->helper->is_hostinger_admin_page() || ! $this->helper->is_woocommerce_site() ) {
			return false;
		}

		$woocommerce_onboarding_profile = get_option( 'woocommerce_onboarding_profile', null );

		// WooCommerce onboarding already done (skipped).
		if ( ! empty( $woocommerce_onboarding_profile['skipped'] ) ) {
			return false;
		}

		// WooCommerce onboarding already done (completed).
		if ( ! empty( $woocommerce_onboarding_profile['completed'] ) ) {
			return false;
		}

		$woo_onboarding_enabled = get_option( 'hostinger_woo_onboarding_enabled', null );

		if ( $woo_onboarding_enabled === null && get_option( 'hts_new_installation', false ) === 'new' ) {
			update_option( 'hostinger_woo_onboarding_enabled', true, false );
			update_option( 'hts_new_installation', 'old' );
		}

		return true;
	}

	public function rate_plugin(): void {
		$promotional_banner_hidden = get_transient( 'hts_hide_promotional_banner_transient' );
		$two_hours_in_seconds      = 7200;

		if ( $promotional_banner_hidden && time() > $promotional_banner_hidden + $two_hours_in_seconds ) {
			require_once HOSTINGER_ABSPATH . 'includes/admin/views/partials/hostinger-rate-us.php';
		}
	}

	public function omnisend_discount_notice(): void {
		$omnisend_notice_hidden = filter_var( isset( $_SESSION['hts_omnisend_notice_hidden'] ) ? $_SESSION['hts_omnisend_notice_hidden'] : false, FILTER_VALIDATE_BOOLEAN );

		if ( ! $omnisend_notice_hidden && ( $this->helper->is_hostinger_admin_page() || $this->helper->is_this_page( '/wp-admin/admin.php?page=omnisend' ) ) && ( Hostinger_Helper::is_plugin_active( 'class-omnisend-core-bootstrap' ) || Hostinger_Helper::is_plugin_active( 'omnisend-woocommerce' ) ) ) : ?>
			<div class="notice notice-info hts-admin-notice hts-omnisend">
				<p><?php echo wp_kses( __( 'Use special discount code <b>ONLYHOSTINGER30</b> to get 30% off for 6 months when you upgrade.', 'hostinger' ), array( 'b' => array() ) ); ?></p>
				<div>
					<a class="button button-primary"
					   href="https://your.omnisend.com/LXqyZ0"
					   target="_blank"><?= esc_html__( 'Get Discount', 'hostinger' ); ?></a>
					<button type="button" class="notice-dismiss"></button>
				</div>
			</div>
		<?php endif;
		wp_nonce_field( 'hts_close_omnisend', 'hts_close_omnisend_nonce', true );
	}

	public function hide_astra_builder_selection_screen(): void {
		add_filter( 'st_enable_block_page_builder', '__return_true' );
	}

	/**
	 *
	 * @param string $classes
	 *
	 * @return string
	 */
	public function add_woocommerce_onboarding_class( string $classes ): string {

		$classes .= ' hostinger-woocommerce-onboarding-page';

		return $classes;
	}

	/**
	 * @return bool
	 */
	public function store_ready_message_logic(): bool {
		if ( ! Hostinger_Helper::is_woocommerce_site() || ! Hostinger_Helper::woocommerce_onboarding_choice() ) {
			return false;
		}

		if ( isset( $_GET['hide-store-notice'] ) ) {
			update_option( 'hostinger_woo_ready_message_shown', 1 );

			return false;
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function show_store_ready_message(): string {
		if ( ! $this->helper->can_show_store_ready_message() ) {
			return '';
		}

		?>
		<div class="notice notice-hst">
			<h3>
				<?php echo esc_html__( 'Your store is ready!', 'hostinger' ); ?>
			</h3>
			<p><?php echo esc_html__( 'One more step to reach your online success. Finalize the visual and technical aspects of your website using our recommendations checklist.', 'hostinger' ); ?></p>
			<p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=hostinger&hide-store-notice' ) ); ?>"
				   class="components-button is-primary"><?php echo esc_html__( 'Visit Hostinger plugin', 'hostinger' ); ?></a>
				<a href="<?php echo esc_url( home_url() ); ?>"
				   target="_blank"
				   class="components-button is-secondary"><?php echo esc_html__( 'Preview store', 'hostinger' ); ?></a>
			</p>
		</div>
		<?php

		return '';
	}

	/**
	 * @return string
	 */
	public function force_woo_notices(): string {
		if ( ! $this->helper->can_show_store_ready_message() ) {
			return '';
		}
		?>
		<style type="text/css">
            .woocommerce-layout__notice-list-hide {
                display: block !important;
            }

            .notice-hst {
                border-left-color: #673DE6;
            }
		</style>
		<?php

		return '';
	}
}

$hostinger_admin_hooks = new Hostinger_Admin_Hooks();
