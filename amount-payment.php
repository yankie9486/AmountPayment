<?php

/**
 * Plugin Name: Amount Payment
 * Plugin URI:
 * Description:
 * Version: 1.0.0
 * Author: Giancarlo Cunis
 * Author URI: http://www.cunisinc.com
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path: /languages
 * Text Domain: amount-payment
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// if (!class_exists('WooCommerce')) {
//     die();
// }

define('AMOUNT_PAYMENT_VERSION', '1.0.0');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-amount-payment.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

    $plugin = new AmountPayment();
    $plugin->run();
}
run_plugin_name();
