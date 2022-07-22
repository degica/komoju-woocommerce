<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

abstract class WC_Gateway_Komoju_Response
{

    public function __construct() {
        add_action( 'komoju_capture_payment', [ $this, 'payment_complete_async' ]);
    }

    /**
     * Get the order from the Komoju 'transaction' variable
     *
     * @param WC_Gateway_Komoju_Webhook_Event $webhookEvent Webhook event data
     * @param string $invoice_prefix set as an option in Komoju plugin dashboard
     *
     * @return bool|WC_Order object
     */
    protected function get_komoju_order($webhookEvent, $invoice_prefix)
    {
        // We have the data in the correct format, so get the order
        if (is_string($webhookEvent->external_order_num())) {
            $order_id = $webhookEvent->external_order_num();

        // Nothing was found
        } else {
            WC_Gateway_Komoju::log('Error: Order ID (external_order_num) was not found in "webhookEvent".');

            return false;
        }

        if (!$order = wc_get_order(substr($order_id, strlen($invoice_prefix), -7))) {
            WC_Gateway_Komoju::log('Error: Cannot locate order in WC with order_id: .' . $order_id . ' minus prefix: ' . $invoice_prefix);

            return false;
        }

        return $order;
    }

    /**
     * Get an order from a payment associated with a KOMOJU session
     *
     * @param string $session_id
     * @param string $invoice_prefix set as an option in Komoju plugin dashboard
     */
    protected function get_order_from_komoju_session($session, $invoice_prefix)
    {
        $order = wc_get_order($session->metadata->woocommerce_order_id);
        if ($order) {
            return $order;
        }

        $payment = $session->payment;
        if (is_null($payment)) {
            return null;
        }

        $order_id = $payment->external_order_num;
        $order    = wc_get_order(substr($order_id, strlen($invoice_prefix), -7));

        if (!$order) {
            WC_Gateway_Komoju::log('Error: Cannot locate order in WC with order_id: .' . $order_id . ' minus prefix: ' . $invoice_prefix);

            return null;
        }

        return $order;
    }

    /**
     * Complete order, add transaction ID and note
     *
     * @param WC_Order $order
     * @param string $txn_id
     * @param string $note
     */
    protected function payment_complete($order, $txn_id = '', $note = '')
    {
        $order_id = $order->get_id();
        as_enqueue_async_action( 'komoju_capture_payment', array($order_id, $note, $txn_id), 'komoju-capture' );
    }

    public function payment_complete_async($order_id, $note, $txn_id)
    {
        $order = wc_get_order($order_id);
        $order->add_order_note($note);
        $order->payment_complete($txn_id);
    }

    /**
     * Hold order and add note
     *
     * @param WC_Order $order
     * @param string $reason
     */
    protected function payment_on_hold($order, $reason = '')
    {
        $order->update_status('on-hold', $reason);
        $order->reduce_order_stock();
        WC()->cart->empty_cart();
    }
}
