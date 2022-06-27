<?php

if (!defined('ABSPATH')) {
    exit;
}

/*
 * Settings for the whole Komoju plugin
 */

return [
    [
        'title'       => 'KOMOJU Account',
        'type'        => 'title',
        'id'          => 'account-settings-in-komoju',
        'desc'        => __('On this page, you can connect your KOMOJU account to your WooCommerce store.'),
    ],
    [
        'id'           => 'komoju_setup_button',
        'type'         => 'komoju_setup_button',
        'title'        => __('Quick Connect', 'komoju-woocommerce'),
    ],
    [
        'id'           => 'komoju_woocommerce_payment_types',
        'type'         => 'komoju_payment_types',
        'title'        => __('Payment Gateways', 'komoju-woocommerce'),
    ],
    [
        'id'       => 'account-settings-in-komoju-end',
        'type'     => 'sectionend',
    ],
];
