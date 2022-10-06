<?php
/**
 * Plugin Name: NR Skroutz CPS Reject
 * Plugin URI:
 * Description: Custom plugin to easily reject orders of Skroutz CPS channel inside Woocommerce admin panel.
 * Version: 0.1
 * Author: Nicholas Rallakis
 * License:
 * License URI:
 */

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

class Nr_Skroutz {
    function __construct() {
        // Nothing to do
    }
}

// Init plugin
if ( is_admin() ) {
    require_once( __DIR__ . '/nr-skroutz-cps-admin.php' );
    $appsbyb_acs_courier = new NR_Skroutz_Reject();
}
