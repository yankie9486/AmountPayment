<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://cunisinc.com
 * @since      1.0.0
 *
 * @package    Amount_Payment
 * @subpackage Amount_Payment/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Amount_Payment
 * @subpackage Amount_Payment/includes
 * @author     Giancarlo Cunis <gcunis@cunisinc.com>
 */
class AmountPayment {
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Amount_Payment_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if (defined('AMOUNT_PAYMENT_VERSION')) {
            $this->version = AMOUNT_PAYMENT_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'amount-payment';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Amount_Payment_Loader. Orchestrates the hooks of the plugin.
     * - Amount_Payment_i18n. Defines internationalization functionality.
     * - Amount_Payment_Admin. Defines all hooks for the admin area.
     * - Amount_Payment_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-amount-payment-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-amount-payment-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-amount-payment-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-amount-payment-public.php';

        $this->loader = new Amount_Payment_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Amount_Payment_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Amount_Payment_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Amount_Payment_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_menu', $plugin_admin, 'amount_payment_menu');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Amount_Payment_Public($this->get_plugin_name(), $this->get_version());

        //public cart
        $this->loader->add_action('init', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_ajax_amount_payment_cart', $plugin_public, 'amount_payment_cart_ajax');
        $this->loader->add_action('wp_ajax_nopriv_amount_payment_cart', $plugin_public, 'amount_payment_cart_ajax');
        $this->loader->add_action('wp_ajax_amount_payment_checkout', $plugin_public, 'amount_payment_checkout_ajax');
        $this->loader->add_action('wp_ajax_nopriv_amount_payment_checkout', $plugin_public, 'amount_payment_checkout_ajax');


        //remove order notes
        $this->loader->add_filter('woocommerce_enable_order_notes_field', $plugin_public, 'disable_order_notes_field', 9999);
        $this->loader->add_filter('woocommerce_checkout_fields', $plugin_public, 'remove_order_notes');

        //add payment field to checkout
        $this->loader->add_filter('woocommerce_checkout_fields', $plugin_public, 'custom_override_checkout_fields');

        //add hidden payment field at checkout
        $this->loader->add_action('woocommerce_before_order_notes', $plugin_public, 'add_verify_payment_checkout_field');

        //Process of Checkout
        $this->loader->add_action('woocommerce_checkout_process', $plugin_public, 'verify_checkout_field_process');
        $this->loader->add_action('woocommerce_before_calculate_totals', $plugin_public, 'recalc_price');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Amount_Payment_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}
