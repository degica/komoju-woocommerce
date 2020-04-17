<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * An encapsulation around the data from Webhook events. This allows us to
 * change the structure of the webhook request data without having to change it
 * throughout the code.
 */
class WC_Gateway_Komoju_Webhook_Event {

    private $requestJson;

    /**
	 * Constructor
	 * @param string $requestBody the body of the webhook request
	 */
    public function __construct($requestBody) {
        // TODO: Handle and log json decoding errors
        $this->requestJson = json_decode($requestBody, true);
    }

    /**
     * A getter to retrieve the event type from the webhook event
     */
    public function event_type() {
        return $this->requestJson['type'];
    }

    private function data() {
        return $this->requestJson['data'];
    }

    /**
     * A getter to retrieve the status of the webhook event
     */
    public function status() {
        return $this->data()['status'];
    }

    /**
     * A getter to retrieve the external_order_num from the webhook event
     */
    public function external_order_num() {
        return $this->data()['external_order_num'];
    }

    /**
     * A getter to retrieve the payment id from the webhook event
     */
    public function uuid() {
        return $this->data()['id'];
    }

    /**
     * A getter to retrieve the currency from the webhook event
     */
    public function currency() {
        return $this->data()['currency'];
    }

    /**
     * A getter to retrieve the total of the payment from the webhook event. This
     * is the price of the purchase + tax + payment_method_fee.
     */
    public function grand_total() {
        return $this->data()['total'];
    }

    /**
     * A getter to retrieve the payment method fee from the webhook event
     */
    public function payment_method_fee() {
        return $this->data()['payment_method_fee'];
    }

    /**
     * A getter to retrieve the additional information from the webhook event
     */
    public function additional_information() {
        // TODO: this field doesn't seem to exist directly on the response
        // in the example webhook data I've got it looks like it has a 'brand'
        // param set to 'master', which is the same as the payment_details object
        return $this->data()['payment_details'];
    }

    /**
     * A getter to retrieve the tax from the webhook event
     */
    public function tax() {
        return $this->data()['tax'];
    }

    /**
     * A getter to retrieve the amount from the webhook event
     */
    public function amount() {
        return $this->data()['amount'];
    }
}
