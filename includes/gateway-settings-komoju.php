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
    'title' => [
        'title'       => __('Title', 'komoju-woocommerce'),
        'type'        => 'text',
        'description' => __('This controls the title which the user sees during checkout.', 'komoju-woocommerce'),
        'default'     => __('Komoju', 'komoju-woocommerce'),
        'desc_tip'    => true,
    ],
    'useOnHold' => [
        'title'       => __('Use on-hold status for pending payments', 'komoju-woocommerce'),
        'type'        => 'checkbox',
        'description' => __("Use 'on-hold' status for payments that are authorized on komoju but awaiting capture. If not selected, 'payment pending' status will be used.", 'komoju-woocommerce'),
        'default'     => 'no',
        'desc_tip'    => true,
    ],
];
