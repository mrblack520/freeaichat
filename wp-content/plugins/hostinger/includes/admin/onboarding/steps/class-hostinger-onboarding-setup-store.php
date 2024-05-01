<?php

class Hostinger_Onboarding_Setup_Store extends Hostinger_Onboarding_Step {
	public function get_title(): string {
		return esc_html__( 'Set up your online store', 'hostinger' );
	}

	public function get_body(): array {
		ob_start();

		?>

		<?php echo esc_html__( 'Complete this following steps to set up your online store. Don\'t worry, WooCommerce will help you to finish the steps.', 'hostinger' ); ?>
		<ul>
			<li>
				<?php echo esc_html__( 'Add products', 'hostinger' ); ?>
			</li>
			<li>
				<?php echo esc_html__( 'Set up payments', 'hostinger' ); ?>
			</li>
			<li>
				<?php echo esc_html__( 'Stock management (if needed)', 'hostinger' ); ?>
			</li>
			<li>
				<?php echo esc_html__( 'Add tax rates (if needed)', 'hostinger' ); ?>
			</li>
			<li>
				<?php echo esc_html__( 'Advertising configuration (if needed)', 'hostinger' ); ?>
			</li>
		</ul>
		<?php

		$description = ob_get_contents();

		ob_end_clean();

		return array(
			array(
				'description' => $description,
			),
		);
	}

	public function step_identifier(): string {
		return 'setup_store';
	}

	public function get_redirect_link(): string {
		return esc_url( admin_url( 'admin.php?page=wc-admin&path=' . rawurlencode( '/setup-wizard' ) ) );
	}

	public function button_text(): string {
		return esc_html__( 'Set up store', 'hostinger' );
	}
}
