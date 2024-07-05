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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Amount_Payment_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Amount_Payment_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        // wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/plugin-name-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Amount_Payment_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Amount_Payment_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        // wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/plugin-name-admin.js', array('jquery'), $this->version, false);
    }

    /**
     * Add option page
     *
     * @since    1.0.0
     */
    public function amount_payment_menu() {
        add_options_page('Amount Payment', 'Amount Payment', 'manage_options', 'amount-payment', [$this, 'amount_payment_options']);
    }


    function amount_payment_options() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $products = wc_get_products();

        var_dump($products);
    }
}
