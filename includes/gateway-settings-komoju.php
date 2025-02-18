<?php

if (!defined('ABSPATH')) {
    exit;
}

/*
 * Gateway-specific Settings for Komoju
 */

return [
    'enabled' => [
        'title'   => __('Enable/Disable', 'komoju-woocommerce'),
        'type'    => 'checkbox',
        'label'   => __('Enable Komoju', 'komoju-woocommerce'),
        'default' => 'no',
    ],
    'showIcon' => [
        'title'       => __('Icon', 'komoju-woocommerce'),
        'label'       => __('Show icon on checkout', 'komoju-woocommerce'),
        'type'        => 'checkbox',
        'default'     => 'yes',
    ],
    'title' => [
        'title'       => __('Title', 'komoju-woocommerce'),
        'type'        => 'text',
        'description' => __('This controls the title which the user sees during checkout.', 'komoju-woocommerce'),
        'default'     => $this->default_title(),
        'desc_tip'    => true,
    ],
    'description' => [
        'title'       => __('Description', 'komoju-woocommerce'),
        'type'        => 'textarea',
        'description' => __('This controls the description which the user sees during checkout.', 'komoju-woocommerce'),
        'default'     => $this->default_description(),
        'desc_tip'    => true,
    ],
    'inlineFields' => [
        'title'       => __('Inline payment fields', 'komoju-woocommerce'),
        'type'        => 'checkbox',
        'description' => __('If checked, this payment method will show fields directly in the checkout page (if supported).', 'komoju-woocommerce'),
        'default'     => 'yes',
        'desc_tip'    => true,
    ],
];
