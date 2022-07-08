<?php
/*
Plugin Name: KOMOJU Payments
Plugin URI: https://github.com/komoju/komoju-woocommerce
Description: Extends WooCommerce with KOMOJU gateway.
Version: 2.4.1
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
        require_once 'includes/class-wc-gateway-komoju-single-slug.php';
        $methods[] = new WC_Gateway_Komoju();

        $komoju_payment_methods = get_option('komoju_woocommerce_payment_methods');
        if (gettype($komoju_payment_methods) == 'array') {
            foreach ($komoju_payment_methods as $payment_method) {
                $methods[] = new WC_Gateway_Komoju_Single_Slug($payment_method);
            }
        }

        return $methods;
    }

    /**
     * Add the KOMOJU settings page to WooCommerce
     **/
    function woocommerce_add_komoju_settings_page($settings)
    {
        require_once 'class-wc-settings-page-komoju.php';
        $settings[] = new WC_Settings_Page_Komoju();

        return $settings;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_komoju_gateway');
    add_filter('woocommerce_get_settings_pages', 'woocommerce_add_komoju_settings_page');
}
