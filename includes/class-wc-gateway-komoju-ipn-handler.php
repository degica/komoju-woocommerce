<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( 'class-wc-gateway-komoju-response.php' );
include_once( 'class-wc-gateway-komoju-webhook-event.php');

/**
 * Handles responses from Komoju IPN
 */
class WC_Gateway_Komoju_IPN_Handler extends WC_Gateway_Komoju_Response {

	protected $webhookSecretToken;
	protected $secret_key;
	protected $invoice_prefix;
	/**
	 * Constructor
	 */
	public function __construct( $webhookSecretToken = '', $secret_key = '', $invoice_prefix = ''  ) {
		add_action( 'woocommerce_api_wc_gateway_komoju', array( $this, 'check_response' ) );
		add_action( 'valid-komoju-standard-ipn-request', array( $this, 'valid_response' ) );

		$this->webhookSecretToken	  	= $webhookSecretToken;
		$this->secret_key	   	        = $secret_key;
		$this->invoice_prefix			= $invoice_prefix;
	}

	/**
	 * Check for Komoju IPN Response
	 */
	public function check_response() {
		$entityBody = file_get_contents('php://input');

		if ( ! empty( $entityBody ) && $this->validate_hmac($entityBody) ) {
			$webhookEvent = new WC_Gateway_Komoju_Webhook_Event($entityBody);

			do_action( "valid-komoju-standard-ipn-request", $webhookEvent );
			exit;
		}
		wp_die( "Komoju IPN Request Failure", "Komoju IPN", array( 'response' => 500 ) );
	}

	/**
	 * There was a valid response
	 * @param  WC_Gateway_Komoju_Webhook_Event $webhookEvent Webhook event data
	 */
	public function valid_response( $webhookEvent ) {
		WC_Gateway_Komoju::log( 'External order num: ' . $webhookEvent->external_order_num() );
		WC_Gateway_Komoju::log( 'Uuid: ' . $webhookEvent->uuid() );
		WC_Gateway_Komoju::log( 'Payment status: ' . $webhookEvent->status() );

		$order = $this->get_komoju_order( $webhookEvent, $this->invoice_prefix );
		if ( $order ) {
			switch($webhookEvent->status()) {
				case "captured":
					$this->payment_status_captured( $order, $webhookEvent );
					break;
				case "authorized":
					$this->payment_status_authorized( $order, $webhookEvent );
					break;
				case "expired":
					$this->payment_status_expired( $order, $webhookEvent );
					break;
				case "cancelled":
					$this->payment_status_cancelled( $order, $webhookEvent );
					break;
				case "failed":
					$this->payment_status_failed( $order, $webhookEvent );
					break;
				case "refunded":
					$this->payment_status_refunded( $order, $webhookEvent );
					break;
				default:
					WC_Gateway_Komoju::log( "Unknown webhook sent. Webhook type: " . $webhookEvent->event_type() );
			}
		}
	}

	/**
	 * Check Komoju IPN validity (hmac control)
	 * @param string $requestBody the body of the request. Needed to correctly
	 * calculate the HMAC for comparison.
	 * @return boolean true/false to indicate whether the hmac is valid
	 */
	public function validate_hmac( $requestBody ) {
		WC_Gateway_Komoju::log( 'Checking if IPN response is valid' );

		// TODO: check if there's a safer way to access the header
		$hmacHeader = $_SERVER['HTTP_X_KOMOJU_SIGNATURE'];
		
		$calcHmac = hash_hmac('sha256', $requestBody, $this->webhookSecretToken);

		if ($hmacHeader != $calcHmac){
			WC_Gateway_Komoju::log( 'hmac codes (sent by Komoju / recalculated) don\'t match. Exiting the process...' );
			return false;
		}
		return true;
	}

	/**
	 * Check currency from IPN matches the order
	 * @param  WC_Order $order
	 * @param  string $currency
	 */
	protected function validate_currency( $order, $currency ) {
		// Validate currency
		if ( $order->get_order_currency() != $currency ) {
			WC_Gateway_Komoju::log( 'Payment error: Currencies do not match (sent "' . $order->get_order_currency() . '" | returned "' . $currency . '")' );

			// Put this order on-hold for manual checking
			$order->update_status( 'on-hold', sprintf( __( 'Validation error: Komoju currencies do not match (code %s).', 'komoju-woocommerce' ), $currency ) );
			exit;
		}
	}

	/**
	 * Check payment amount from IPN matches the order
	 * @param  WC_Order $order
	 * @param int $amount the order amount
	 */
	protected function validate_amount( $order, $amount ) {
		if ( number_format( $order->get_total(), 2, '.', '' ) != number_format( $amount, 2, '.', '' ) ) {
			WC_Gateway_Komoju::log( 'Payment error: Amounts do not match (total: ' . $amount . ') for order #'.$order->id.'('.$order->get_total().')' );

			// Put this order on-hold for manual checking
			$order->update_status( 'on-hold', sprintf( __( 'Validation error: Komoju amounts do not match (total %s).', 'komoju-woocommerce' ), $amount ) );
			exit;
		}
	}

	/**
	 * Handle a captured payment
	 * @param  WC_Order $order
	 */
	protected function payment_status_captured( $order, $webhookEvent ) {
		if ( $order->has_status( 'captured' ) ) {
			WC_Gateway_Komoju::log( 'Aborting, Order #' . $order->id . ' is already complete.' );
			exit;
		}
		
		$this->validate_currency( $order, $webhookEvent->currency() );
		$this->validate_amount( $order, $webhookEvent->grand_total() - $webhookEvent->payment_method_fee() ); 
		$this->save_komoju_meta_data( $order, $webhookEvent );

		if ( 'captured' === $webhookEvent->status() ) {
			$this->payment_complete( $order, ( ! empty( $webhookEvent->external_order_num() ) ? wc_clean( $webhookEvent->external_order_num() ) : '' ), __( 'IPN payment captured', 'komoju-woocommerce' ) );

			if ( ! empty( $webhookEvent->payment_method_fee() ) ) {
				// log komoju transaction fee
				update_post_meta( $order->id, 'Payment Gateway Transaction Fee', wc_clean( $webhookEvent->payment_method_fee() ) );
			}

		} else {
			$this->payment_on_hold( $order, sprintf( __( 'Payment pending: %s', 'woocommerce-konomu' ), $webhookEvent->additional_information() ) );
		}
	}

	/**
	 * Handle a cancelled payment
	 * @param  WC_Order $order
	 */
	protected function payment_status_cancelled( $order, $webhookEvent ) {
		$order->update_status( 'cancelled', sprintf( __( 'Payment %s via IPN.', 'komoju-woocommerce' ), wc_clean( $webhookEvent->status() ) ) );
	}

	protected function payment_status_failed( $order, $webhookEvent ) {
		$this->payment_status_cancelled( $order, $webhookEvent );
	}

	/**
	 * Handle an expired payment
	 * @param  WC_Order $order
	 */
	protected function payment_status_expired( $order, $webhookEvent ) {
		$this->payment_status_cancelled( $order, $webhookEvent );
	}

	/**
	 * Handle an authorized payment
	 * @param  WC_Order $order
	 */
	protected function payment_status_authorized( $order, $webhookEvent ) {
		update_post_meta( $order->id, sprintf( __( 'Payment %s via IPN.', 'komoju-woocommerce' ), wc_clean( webhookResponse.status() ) ) );
	}

	/**
	 * Handle a refunded order
	 * @param  WC_Order $order
	 */
	protected function payment_status_refunded( $order, $webhookEvent ) {
		// Only handle full refunds, not partial
		WC_Gateway_Komoju::log( 'Only handling full refund. Controlling that order total equals amount refunded. Does '.$order->get_total().' equals '.$webhookEvent->grand_total().' ?' );
		if ( $order->get_total() == ( $webhookEvent->amount_refunded() ) ) {

			// Mark order as refunded
			$order->update_status( 'refunded', sprintf( __( 'Payment %s via IPN.', 'komoju-woocommerce' ), strtolower( $webhookEvent->status() ) ) );

			/*$this->send_ipn_email_notification(
				sprintf( __( 'Payment for order #%s refunded/reversed', 'woocommerce' ), $order->get_order_number() ),
				sprintf( __( 'Order #%s has been marked as refunded - Komoju reason code: %s', 'woocommerce' ), $order->get_order_number(), $posted['reason_code'] )
			);*/
		}
	}

	/**
	 * Save important data from the IPN to the order
	 * @param WC_Order $order
	 */
	protected function save_komoju_meta_data( $order, $webhookEvent ) {
		if ( ! empty( $webhookEvent->tax() ) ) {
			update_post_meta( $order->id, 'Tax', wc_clean( $webhookEvent->tax() ) );
		}
		if ( ! empty( $webhookEvent->amount() ) ) {
			update_post_meta( $order->id, 'Amount', wc_clean( $webhookEvent->amount() ) );
		}
		if ( ! empty( $webhookEvent->additional_information() ) ) {
			update_post_meta( $order->id, 'Additional info', wc_clean( print_r( $webhookEvent->additional_information(), true) ) );
		}
	}

	/**
	 * Send a notification to the user handling orders.
	 * @param  string $subject
	 * @param  string $message
	 */
	/*protected function send_ipn_email_notification( $subject, $message ) {
		$new_order_settings = get_option( 'woocommerce_new_order_settings', array() );
		$mailer             = WC()->mailer();
		$message            = $mailer->wrap_message( $subject, $message );

		$mailer->send( ! empty( $new_order_settings['recipient'] ) ? $new_order_settings['recipient'] : get_option( 'admin_email' ), $subject, $message );
	}*/
}
