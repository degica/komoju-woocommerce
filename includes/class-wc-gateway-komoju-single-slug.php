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
        $slug = $payment_method['type_slug'];

        $this->payment_method = $payment_method;
        $this->id             = 'komoju_' . $slug;
        $this->has_fields     = false;
        $this->method_title   = __('Komoju', 'komoju-woocommerce') . ' - ' . $this->default_title();

        if ($this->get_option('showIcon') == 'yes') {
            $this->icon = "https://komoju.com/payment_methods/$slug.svg";
        }

        // TODO: It would be nice if KOMOJU told us in the payment method object whether or
        // not it supports refunds. For now, we'll just wing it.
        if (!in_array($slug, ['konbini', 'pay_easy', 'bank_transfer'])) {
            $this->supports[] = 'refunds';
        }

        parent::__construct();
    }

    /**
     * Process refund.
     *
     * Attempts to refund the passed-in amount with KOMOJU.
     *
     * @param int $order_id order ID
     * @param float|null $amount refund amount
     * @param string $reason refund reason
     *
     * @return bool true or false based on success, or a WP_Error object
     */
    public function process_refund($order_id, $amount = null, $reason = '')
    {
        $order      = wc_get_order($order_id);
        $payment_id = $order->get_meta('komoju_payment_id');

        if ($payment_id == '') {
            return false;
        }

        $payload = [];
        if (!is_null($amount)) {
            $payload['amount'] = $amount;
        }
        if ($reason != '') {
            $payload['description'] = $reason;
        }

        try {
            $payment = $this->komoju_api->refund($payment_id, $payload);
        } catch (KomojuExceptionBadServer | KomojuExceptionBadJson $e) {
            $error_message = $e->getMessage();
            $this->log($error_message);

            return new WP_Error('komoju_refund_failed', $error_message);
        }

        $refund = $payment->refunds[count($payment->refunds) - 1];

        if ($refund && $refund->amount == $amount) {
            return true;
        } else {
            return false;
        }
    }

    public function validate_fields()
    {
        return true;
    }

    public function payment_fields()
    {
        // No fields!
    }

    public function process_payment($order_id, $payment_type = null)
    {
        return parent::process_payment($order_id, $this->payment_method['type_slug']);
    }

    public function default_title()
    {
        return $this->payment_method['name_' . WC_Gateway_Komoju::get_locale_or_fallback()];
    }
}
