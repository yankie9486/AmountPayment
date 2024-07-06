<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cunisinc.com
 * @since      1.0.0
 *
 * @package    Amount_Payment
 * @subpackage Amount_Payment/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Amount_Payment
 * @subpackage Amount_Payment/includes
 * @author     Giancarlo Cunis <gcunis@cunisinc.com>
 */
class Amount_Payment_Setting {

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

    function wpse61431_register_settings() {
        //this will save the option in the wp_options table as 'wpse61431_settings'
        //the third parameter is a function that will validate your input values
        register_setting('wpse61431_settings', 'wpse61431_settings', 'wpse61431_settings_validate');
    }
}
