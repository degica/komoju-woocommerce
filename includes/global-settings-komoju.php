<?php

if (!defined('ABSPATH')) {
    exit;
}

/*
 * Settings for the whole Komoju plugin
 */

return [
    [
        'title'       => 'API Settings',
        'type'        => 'title',
        'id'          => 'api-settings-in-komoju',
        'desc'        => sprintf(__('Default url for the webhook is %s. Use this if you\'re not sure what it should be.', 'komoju-woocommerce'), $this->url_for_webhooks()),
    ],
    [
        'id'          => 'komoju_woocommerce_payment_methods',
        'type'        => 'komoju_payment_methods',
        'title'        => __('Payment Gateways'),
    ],
    [
        'id'          => 'komoju_woocommerce_secret_key',
        'placeholder' => 'sk_live_000000000000000000000000',
        'title'       => __('Secret Key from Komoju', 'komoju-woocommerce'),
        'type'        => 'text',
        'default'     => get_option('woocommerce_komoju_settings')['secretKey'],
        'desc_tip'    => true,
    ],
    [
        'id'          => 'komoju_woocommerce_webhook_secret',
        'placeholder' => __('Please enter your Komoju Webhook Secret Token', 'komoju-woocommerce'),
        'title'       => __('Webhook Secret Token', 'komoju-woocommerce'),
        'type'        => 'text',
        'default'     => get_option('woocommerce_komoju_settings')['webhookSecretToken'],
        'desc_tip'    => true,
    ],
    [
        'id'          => 'komoju_woocommerce_invoice_prefix',
        'placeholder' => __('Please enter a prefix for your invoice numbers. If you use your Komoju account for multiple stores ensure this prefix is unique.', 'komoju-woocommerce'),
        'title'       => __('Invoice Prefix', 'komoju-woocommerce'),
        'type'        => 'text',
        'default'     => get_option('woocommerce_komoju_settings')['invoice_prefix'] ?
                         get_option('woocommerce_komoju_settings')['invoice_prefix'] : 'WC-',
        'desc_tip'    => true,
    ],
    [
        'id'          => 'komoju_woocommerce_debug_log',
        'desc'        => sprintf(__('Log Komoju events inside <code>%s</code>', 'komoju-woocommerce'), wc_get_log_file_path('komoju')),
        'desc_tip'    => true,
        'title'       => __('Debug Log', 'komoju-woocommerce'),
        'type'        => 'checkbox',
        'label'       => __('Enable logging', 'komoju-woocommerce'),
        'default'     => get_option('woocommerce_komoju_settings')['debug'] ?
                         get_option('woocommerce_komoju_settings')['debug'] : 'no',
    ],
    [
        'id'       => 'api-settings-in-komoju-end',
        'type'     => 'sectionend',
    ]
];
