<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cunisinc.com
 * @since      1.0.0
 *
 * @package    Amount_Payment
 * @subpackage Amount_Payment/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Amount_Payment
 * @subpackage Amount_Payment/admin
 * @author     Giancarlo Cunis <gcunis@cunisinc.com>
 */
class Amount_Payment_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Add option page
	 *
	 * @since    1.0.0
	 */
	public function amount_payment_menu() {
		add_options_page( 'Amount Payment', 'Amount Payment', 'manage_options', 'amount-payment', array( $this, 'amount_payment_options' ) );
	}

	/**
	 * Admin Page
	 *
	 * @return void
	 */
	public function amount_payment_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html_e( 'You do not have sufficient permissions to access this page.', 'amount-payment' ) );
		}

		$args     = array( 'limit' => 10 );
		$products = \wc_get_products( $args );

		include plugin_dir_path( __FILE__ ) . 'partials/amount-payment-admin-page.php';
	}

	/**
	 * Register setting for saving
	 *
	 * @return void
	 */
	public function register_settings() {
		// this will save the option in the wp_options table as 'amount_payment_settings'.
		register_setting( 'amount_payment_settings', 'amount_payment_settings', 'amount_payment_settings_validate' );
	}

	/**
	 * Validate fields saving
	 *
	 * @param array $args
	 * @return array
	 */
	public function amount_payment_settings_validate( $args ) {
		if ( ! isset( $args['amount_payment_product'] ) ) {
			$args['amount_payment_product'] = null;
			add_settings_error( 'amount_payment_settings', 'amount_payment_select_product', 'Please enter a Product!', $type = 'error' );
		}

		return $args;
	}

	/**
	 * Admin error sent to notices.
	 *
	 * @return void
	 */
	public function admin_notices() {
		settings_errors();
	}
}
