<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

include_once 'class-wc-gateway-komoju-response.php';
include_once 'class-wc-gateway-komoju-webhook-event.php';

/**
 * Specialized version of WC_Gateway_Komoju that provides only one payment method at a time.
 */
class WC_Gateway_Komoju_Single_Slug extends WC_Gateway_Komoju
{
    public function __construct($payment_method)
    {
        $this->payment_method = $payment_method;
        $this->id             = 'komoju_' . $payment_method['type_slug'];
        $this->has_fields     = false;
        $this->method_title   = __('Komoju', 'komoju-woocommerce') . ' - ' . $this->default_title();

        parent::__construct();
    }

    public function validate_fields()
    {
        return true;
    }

    public function payment_fields()
    {
        // No fields!
    }

    public function process_payment($order_id, $payment_method = null)
    {
        return parent::process_payment($order_id, $this->payment_method);
    }

    public function default_title()
    {
        return $this->payment_method['name_' . WC_Gateway_Komoju::get_locale_or_fallback()];
    }
}
