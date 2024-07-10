<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cunisinc.com
 * @since      1.0.
 *
 * @package    Amount_Payment
 * @subpackage Amount_Payment/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Amount_Payment
 * @subpackage Amount_Payment/public
 * @author     Giancarlo Cunis <gcunis@cunisinc.com>
 */
class Amount_Payment_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// Register Cart.
		wp_register_script(
			$this->plugin_name . '-cart',
			plugin_dir_url( __FILE__ ) . 'js/amount-payment.js',
			array( 'jquery' ),
			$this->version,
			false
		);
		$nonce_cart = wp_create_nonce( 'amount-payment-cart-nonce' );

		wp_localize_script(
			$this->plugin_name . '-cart',
			'payCart',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => $nonce_cart,
			)
		);

		// Register Checkout.
		wp_register_script(
			$this->plugin_name . '-checkout',
			plugin_dir_url( __FILE__ ) . 'js/amount-payment-checkout.js',
			array( 'jquery' ),
			$this->version,
			false
		);
		$nonce_checkout = wp_create_nonce( 'amount-payment-checkout-nonce' );
		wp_localize_script(
			$this->plugin_name . '-checkout',
			'payCheckout',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => $nonce_checkout,
			)
		);

		// enqueue jQuery library and the script you registered above.
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( $this->plugin_name . '-cart' );
		wp_enqueue_script( $this->plugin_name . '-checkout' );
	}

	/**
	 * Cart AJAX
	 */
	public function amount_payment_cart_ajax() {

		if (
			! isset( $_POST['nonce'] )
			|| ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'amount_payment_cart' )
		) {

			global $woocommerce;
			$items = $woocommerce->cart->get_cart();
			if ( count( $items ) === 0 ) {
				$options          = get_option( 'amount_payment_settings' );
				$selected_product = ( isset( $options['amount_payment_product'] ) ) ? intval( $options['amount_payment_product'] ) : null;
				$woocommerce->cart->add_to_cart( $selected_product, 1 );
				wp_send_json(
					array(
						'redirect' => \wc_get_checkout_url(),
						'status'   => 'Added to checkout',
						'type'     => 'success',
					)
				);
			} else {
				wp_send_json(
					array(
						'redirect' => \wc_get_checkout_url(),
						'status'   => 'Already in the checkout',
						'type'     => 'success',
					)
				);
			}
		}
	}

	/**
	 * Checkout AJAX
	 */
	public function amount_payment_checkout_ajax() {

		if ( ! isset( $_POST['nonce'] )
			|| ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'amount_payment_checkout' ) ) {
			return;
		}

		if ( isset( $_POST['payment_amount'] ) ) {
			\WC()->session->set( 'payment_amount_total', sanitize_text_field( wp_unslash( $_POST['payment_amount'] ) ) );
			wp_send_json(
				array(
					'pay'  => sanitize_text_field( wp_unslash( $_POST['payment_amount'] ) ),
					'type' => 'success',
				)
			);
		}
	}

	/**
	 * Add Textbox to checkout field
	 *
	 * @param array $fields
	 * @return array
	 */
	public function add_amount_checkout_fields( $fields ) {

		$amount_field = array(
			'label'       => __( 'Amount', 'amount-payment' ),
			'placeholder' => _x( 'Amount', 'placeholder', 'amount-payment' ),
			'required'    => true,
			'class'       => array( 'w-full' ),
			'clear'       => true,
		);

		$fields['billing'] = array( 'payment_amount' => $amount_field ) + $fields['billing'];
		return $fields;
	}

	/**
	 * Remove Order Notes Field
	 *
	 * @param array $fields
	 * @return array
	 */
	public function remove_order_notes( $fields ) {
		unset( $fields['order']['order_comments'] );
		return $fields;
	}

	public function add_verify_payment_checkout_field() {

		echo '<input type="hidden" id="verify_payment_amount" name="verify_payment_amount" />';
	}

	public function disable_order_notes_field() {
		return false;
	}

	/**
	 * Vailds checkbox and dob
	 */
	public function verify_checkout_field_process() {

		if ( ! isset( $_POST['nonce'] )
			|| ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'amount_payment_checkout' )
		) {
			return;
		}

		$payment_amount        = ( isset( $_POST['payment_amount'] ) ) ? sanitize_text_field( wp_unslash( $_POST['payment_amount'] ) ) : '';
		$verify_payment_amount = ( isset( $_POST['verify_payment_amount'] ) ) ? sanitize_text_field( wp_unslash( $_POST['verify_payment_amount'] ) ) : '';
		$payment_amount_total  = ( null !== WC()->session->get( 'payment_amount_total' ) ) ? WC()->session->get( 'payment_amount_total' ) : null;

		if (
			( 0 === $payment_amount || '' === $payment_amount ) &&
			( 0 !== $payment_amount_total || null === $payment_amount_total )
		) {
			wc_add_notice( __( 'Please enter a payment amount.', 'amount-payment' ), 'error' );
		}

		if ( '' === $verify_payment_amount || $payment_amount !== $verify_payment_amount ) {
			wc_add_notice( __( 'Please enter a payment amount.', 'amount-payment' ), 'error' );
		}
	}

	/**
	 * Recalculate Cart
	 *
	 * @param object $cart_object
	 * @return void
	 */
	public function recalc_price( $cart_object ) {
		wp_die(var_dump($cart_object));
		$payment = ( ! empty( WC()->session->get( 'payment_amount_total' ) ) ) ? WC()->session->get( 'payment_amount_total' ) : 0;

		foreach ( $cart_object->get_cart() as $hash => $value ) {
			$value['data']->set_price( $payment );
		}
	}

	public function change_button_text( $button_text ) {
		$button_text = __( 'Make a Payment', 'amount-payment' );
		return $button_text;
	}

	/**
	 * Auto Complete all WooCommerce orders.
	 *
	 * @param int $order_id
	 * @return void
	 */
	public function new_order_auto_complete_order( $order_id ) {
		if ( ! $order_id ) {
			return;
		}

		$order = \wc_get_order( $order_id );
		$order->update_status( 'completed' );
	}
}
