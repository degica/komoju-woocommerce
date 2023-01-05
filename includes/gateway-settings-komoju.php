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
    'useOnHold' => [
        'title'       => __('Use on-hold status for pending payments', 'komoju-woocommerce'),
        'type'        => 'checkbox',
        'description' => __("Use 'on-hold' status for payments that are authorized on komoju but awaiting capture. If not selected, 'payment pending' status will be used.", 'komoju-woocommerce'),
        'default'     => 'no',
        'desc_tip'    => true,
    ],
    'inlineFields' => [
        'title'       => __('Inline payment fields', 'komoju-woocommerce'),
        'type'        => 'checkbox',
        'description' => __('If checked, this payment method will show fields directly in the checkout page.', 'komoju-woocommerce'),
        'default'     => 'yes',
        'desc_tip'    => true,
    ],
];
