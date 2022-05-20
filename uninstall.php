<?php
/**
 * WooCommerce Komoju Payment Gateway
 * Uninstall - removes all options from DB when user deletes the plugin via WordPress backend.
 *
 * @since 1.0
 *
 **/
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

delete_option('woocommerce_komoju_settings');
delete_option('komoju_woocommerce_secret_key');
delete_option('komoju_woocommerce_webhook_secret');
delete_option('komoju_woocommerce_invoice_prefix');
delete_option('komoju_woocommerce_debug_log');
