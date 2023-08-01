<?php
/*
Plugin Name: KOMOJU Payments
Plugin URI: https://github.com/komoju/komoju-woocommerce
Description: Extends WooCommerce with KOMOJU gateway.
Version: 3.0.3
Author: KOMOJU
Author URI: https://komoju.com
*/

add_action('plugins_loaded', 'woocommerce_komoju_init', 0);

function woocommerce_komoju_init()
{
    /*
     * Localisation
     */
    load_plugin_textdomain('komoju-woocommerce', false, dirname(plugin_basename(__FILE__)) . '/languages');

    /**
     * Add the Gateway to WooCommerce
     **/
    function woocommerce_add_komoju_gateway($methods)
    {
        require_once 'class-wc-gateway-komoju.php';
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
        require_once 'class-wc-gateway-komoju.php';
        require_once 'class-wc-settings-page-komoju.php';
        $settings[] = new WC_Settings_Page_Komoju();

        return $settings;
    }

    /**
     * Add the KOMOJU Fields JS
     **/
    function woocommerce_komoju_load_scripts()
    {
        if (!is_checkout()) {
            return;
        }

        $komoju_fields_js = get_option('komoju_woocommerce_fields_url');
        if (!$komoju_fields_js) {
            $komoju_fields_js = 'https://multipay.komoju.com/fields.js';
        }

        wp_enqueue_script('komoju-fields', $komoju_fields_js);
    }
    function woocommerce_komoju_load_script_as_module($tag, $handle, $src)
    {
        if ($handle !== 'komoju-fields') {
            return $tag;
        }

        return '<script type="module" src="' . esc_attr($src) . '"></script>';
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_komoju_gateway');
    add_filter('woocommerce_get_settings_pages', 'woocommerce_add_komoju_settings_page');

    add_action('wp_enqueue_scripts', 'woocommerce_komoju_load_scripts');
    add_filter('script_loader_tag', 'woocommerce_komoju_load_script_as_module', 10, 3);
}
