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
        'id'          => 'komoju_woocommerce_secret_key',
        'placeholder' => 'sk_live_000000000000000000000000',
        'title'       => __('Secret Key from Komoju', 'komoju-woocommerce'),
        'type'        => 'text',
        'default'     => WC_Gateway_Komoju::get_legacy_setting('secretKey'),
        'desc_tip'    => true,
    ],
    [
        'id'          => 'komoju_woocommerce_publishable_key',
        'placeholder' => 'pk_live_000000000000000000000000',
        'title'       => __('Publishable Key from Komoju', 'komoju-woocommerce'),
        'type'        => 'text',
        'default'     => WC_Gateway_Komoju::get_legacy_setting('publishableKey'),
        'desc_tip'    => true,
    ],
    [
        'id'          => 'komoju_woocommerce_webhook_secret',
        'placeholder' => __('Please enter your Komoju Webhook Secret Token', 'komoju-woocommerce'),
        'title'       => __('Webhook Secret Token', 'komoju-woocommerce'),
        'type'        => 'text',
        'default'     => WC_Gateway_Komoju::get_legacy_setting('webhookSecretToken'),
        'desc_tip'    => true,
    ],
    [
        'id'          => 'komoju_woocommerce_invoice_prefix',
        'placeholder' => __('Please enter a prefix for your invoice numbers. If you use your Komoju account for multiple stores ensure this prefix is unique.', 'komoju-woocommerce'),
        'title'       => __('Invoice Prefix', 'komoju-woocommerce'),
        'type'        => 'text',
        'default'     => WC_Gateway_Komoju::get_legacy_setting('invoice_prefix', 'WC-'),
        'desc_tip'    => true,
    ],
    [
        'id'           => 'komoju_woocommerce_ipn_async',
        'type'         => 'checkbox',
        'title'        => __('Process IPNs Asynchronously', 'komoju-woocommerce'),
        'desc'         => __('When true, IPNs will return immediately, and order completion will be processed in the background.', 'komoju-woocommerce'),
        'default'      => 'no',
    ],
    [
        'id'          => 'komoju_woocommerce_debug_log',
        'desc'        => sprintf(__('Log Komoju events inside <code>%s</code>', 'komoju-woocommerce'), wc_get_log_file_path('komoju')),
        'desc_tip'    => true,
        'title'       => __('Debug Log', 'komoju-woocommerce'),
        'type'        => 'checkbox',
        'label'       => __('Enable logging', 'komoju-woocommerce'),
        'default'     => WC_Gateway_Komoju::get_legacy_setting('debug', 'no'),
    ],
    [
        'id'          => 'komoju_woocommerce_api_endpoint',
        'title'       => __('KOMOJU Endpoint', 'komoju-woocommerce'),
        'type'        => 'komoju_endpoint',
        'default'     => KomojuApi::defaultEndpoint(),
    ],
    [
        'id'          => 'komoju_woocommerce_fields_url',
        'title'       => __('KOMOJU Fields script URL', 'komoju-woocommerce'),
        'type'        => 'komoju_endpoint',
        'default'     => 'https://multipay.komoju.com/fields.js',
    ],
    [
        'id'          => 'komoju_woocommerce_waf_staging_token',
        'desc'        => __('Usually you want this to be empty.', 'komoju-woocommerce'),
        'title'       => __('Staging token', 'komoju-woocommerce'),
        'type'        => 'text',
        'default'     => '',
    ],
    [
        'id'       => 'api-settings-in-komoju-end',
        'type'     => 'sectionend',
    ],
];
