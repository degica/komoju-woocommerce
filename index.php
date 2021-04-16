<?php
/*
Plugin Name: KOMOJU
Plugin URI: https://github.com/komoju/komoju-woocommerce
Description: Extends WooCommerce with KOMOJU gateway.
Version: 2.0.0
Author: KOMOJU
Author URI: https://komoju.com
*/

add_action('plugins_loaded', 'woocommerce_komoju_init', 0);

function woocommerce_komoju_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    /*
     * Localisation
     */
    load_plugin_textdomain('komoju-woocommerce', false, dirname(plugin_basename(__FILE__)) . '/languages');

    require_once 'class-wc-gateway-komoju.php';

    /**
     * Add the Gateway to WooCommerce
     **/
    function woocommerce_add_komoju_gateway($methods)
    {
        $methods[] = 'WC_Gateway_Komoju';

        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_komoju_gateway');
}
