<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

abstract class WC_Gateway_Komoju_Response
{
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
     * Complete order, add transaction ID and note
     *
     * @param WC_Order $order
     * @param string $txn_id
     * @param string $note
     */
    protected function payment_complete($order, $txn_id = '', $note = '')
    {
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
