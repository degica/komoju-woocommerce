<?php

if (!defined('ABSPATH')) {
    exit;
}

/*
 * Settings for Komoju Gateway (hosted page)
 */

return [
    'enabled' => [
        'title'   => __('Enable/Disable', 'komoju-woocommerce'),
        'type'    => 'checkbox',
        'label'   => __('Enable Komoju', 'komoju-woocommerce'),
        'default' => 'no',
    ],
    'title' => [
        'title'       => __('Title', 'komoju-woocommerce'),
        'type'        => 'text',
        'description' => __('This controls the title which the user sees during checkout.', 'komoju-woocommerce'),
        'default'     => __('Komoju', 'komoju-woocommerce'),
        'desc_tip'    => true,
    ],
    'API_settings' => [
        'title'       => 'API Settings',
        'type'        => 'title',
        'id'          => 'api-settings-in-komoju',
        'description' => sprintf(__('Default url for the webhook is %s. Use this if you\'re not sure what it should be.', 'komoju-woocommerce'), $this->get_mydefault_api_url()),
    ],
    'accountID' => [
        'title'       => __('Komoju merchant ID', 'komoju-woocommerce'),
        'type'        => 'text',
        'description' => __('Please enter your Komoju account ID.', 'komoju-woocommerce'),
        'default'     => '',
        'desc_tip'    => true,
    ],
    'secretKey' => [
        'title'       => __('Secret Key from Komoju', 'komoju-woocommerce'),
        'type'        => 'text',
        'description' => __('Please enter your Komoju secret key.', 'komoju-woocommerce'),
        'default'     => '',
        'desc_tip'    => true,
    ],
    'webhookSecretToken' => [
        'title'       => __('Webhook Secret Token', 'komoju-woocommerce'),
        'type'        => 'text',
        'description' => __('Please enter your Komoju Webhook Secret Token', 'komoju-woocommerce'),
        'default'     => '',
        'desc_tip'    => true,
    ],
    'invoice_prefix' => [
        'title'       => __('Invoice Prefix', 'komoju-woocommerce'),
        'type'        => 'text',
        'description' => __('Please enter a prefix for your invoice numbers. If you use your Komoju account for multiple stores ensure this prefix is unique.', 'komoju-woocommerce'),
        'default'     => 'WC-',
        'desc_tip'    => true,
    ],
    'debug' => [
        'title'       => __('Debug Log', 'komoju-woocommerce'),
        'type'        => 'checkbox',
        'label'       => __('Enable logging', 'komoju-woocommerce'),
        'default'     => 'no',
        'description' => sprintf(__('Log Komoju events inside <code>%s</code>', 'komoju-woocommerce'), wc_get_log_file_path('komoju')),
    ],
];
