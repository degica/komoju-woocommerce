<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( 'class-wc-gateway-komoju-response.php' );

/**
 * Handles responses from Komoju IPN
 */
class WC_Gateway_Komoju_IPN_Handler extends WC_Gateway_Komoju_Response {

	protected $notify_url;
	protected $secret_key;
	protected $invoice_prefix;
	/**
	 * Constructor
	 */
	public function __construct( $notify_url = '', $secret_key = '', $invoice_prefix = ''  ) {
		add_action( 'woocommerce_api_wc_gateway_komoju', array( $this, 'check_response' ) );
		add_action( 'valid-komoju-standard-ipn-request', array( $this, 'valid_response' ) );

		$this->notify_url	  	= $notify_url;
		$this->secret_key	  	= $secret_key;
		$this->invoice_prefix	= $invoice_prefix;
	}

	/**
	 * Check for Komoju IPN Response
	 */
	public function check_response() {
		if ( ! empty( $_GET ) && $this->validate_hmac() ) {
			$posted = wp_unslash( $_GET );

			do_action( "valid-komoju-standard-ipn-request", $posted['transaction'] );
			exit;
		}
		wp_die( "Komoju IPN Request Failure", "Komoju IPN", array( 'response' => 500 ) );
	}

	/**
	 * There was a valid response
	 * @param  array $posted Post data after wp_unslash
	 */
	public function valid_response( $posted ) {
		WC_Gateway_Komoju::log( 'External order num: ' . $posted['external_order_num'] );
		WC_Gateway_Komoju::log( 'Uuid: ' . $posted['uuid'] );
		WC_Gateway_Komoju::log( 'Payment status: ' . $posted['status'] );

		$order = $this->get_komoju_order( $posted, $this->invoice_prefix );
		if ( $order ) {
			if ( method_exists( $this, 'payment_status_' . $posted['status'] ) ) {
				call_user_func( array( $this, 'payment_status_' . $posted['status'] ), $order, $posted );
			}
		}
	}

	/**
	 * Check Komoju IPN validity (hmac control)
	 */
	public function validate_hmac() {
		WC_Gateway_Komoju::log( 'Checking if IPN response is valid' );

		// Get post data
		$posted = wp_unslash( $_SERVER['QUERY_STRING'] ); 
		$get = wp_unslash( $_GET );
		$komojuHmac = $get['hmac'];
		$str = substr($posted, strpos($posted, 'timestamp') );
		
		// Recalculate the hmac code here in order to compare values
		$_url = parse_url($this->notify_url);
		$url = $_url['path']. '?' .$str;
		$calcHmac = hash_hmac('sha256', $url, $this->secret_key);

		if ($komojuHmac != $calcHmac){
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
	protected function payment_status_captured( $order, $posted ) {
		if ( $order->has_status( 'captured' ) ) {
			WC_Gateway_Komoju::log( 'Aborting, Order #' . $order->id . ' is already complete.' );
			exit;
		}
		
		$this->validate_currency( $order, $posted['currency'] );
		$this->validate_amount( $order, $posted['grand_total']-$posted['payment_method_fee'] ); 
		$this->save_komoju_meta_data( $order, $posted );

		if ( 'captured' === $posted['status'] ) {
			$this->payment_complete( $order, ( ! empty( $posted['external_order_num'] ) ? wc_clean( $posted['external_order_num'] ) : '' ), __( 'IPN payment captured', 'komoju-woocommerce' ) );

			if ( ! empty( $posted['payment_method_fee'] ) ) {
				// log komoju transaction fee
				update_post_meta( $order->id, 'Payment Gateway Transaction Fee', wc_clean( $posted['payment_method_fee'] ) );
			}

		} else {
			$this->payment_on_hold( $order, sprintf( __( 'Payment pending: %s', 'woocommerce-konomu' ), $posted['additional_information'] ) );
		}
	}

	/**
	 * Handle a pending payment
	 * @param  WC_Order $order
	 */
	protected function payment_status_pending( $order, $posted ) {
		$this->payment_status_captured( $order, $posted );
	}

	/**
	 * Handle a cancelled payment
	 * @param  WC_Order $order
	 */
	protected function payment_status_cancelled( $order, $posted ) {
		$order->update_status( 'cancelled', sprintf( __( 'Payment %s via IPN.', 'komoju-woocommerce' ), wc_clean( $posted['status'] ) ) );
	}

	/**
	 * Handle a denied payment
	 * @param  WC_Order $order
	 */
	/*protected function payment_status_denied( $order, $posted ) {
		$this->payment_status_failed( $order, $posted );
	}*/

	/**
	 * Handle an expired payment
	 * @param  WC_Order $order
	 */
	protected function payment_status_expired( $order, $posted ) {
		$this->payment_status_cancelled( $order, $posted );
	}

	/**
	 * Handle an authorized payment
	 * @param  WC_Order $order
	 */
	protected function payment_status_authorized( $order, $posted ) {
		update_post_meta( $order->id, sprintf( __( 'Payment %s via IPN.', 'komoju-woocommerce' ), wc_clean( $posted['status'] ) ) );
	}

	/**
	 * Handle a refunded order
	 * @param  WC_Order $order
	 */
	protected function payment_status_refunded( $order, $posted ) {
		// Only handle full refunds, not partial
		WC_Gateway_Komoju::log( 'Only handling full refund. Controlling that order total equals amount refunded. Does '.$order->get_total().' equals '.$posted['grand_total'].' ?' );
		if ( $order->get_total() == ( $posted['grand_total'] ) ) {

			// Mark order as refunded
			$order->update_status( 'refunded', sprintf( __( 'Payment %s via IPN.', 'komoju-woocommerce' ), strtolower( $posted['status'] ) ) );

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
	protected function save_komoju_meta_data( $order, $posted ) {
		if ( ! empty( $posted['tax'] ) ) {
			update_post_meta( $order->id, 'Tax', wc_clean( $posted['tax'] ) );
		}
		if ( ! empty( $posted['amount'] ) ) {
			update_post_meta( $order->id, 'Amount', wc_clean( $posted['amount'] ) );
		}
		if ( ! empty( $posted['additional_information'] ) ) {
			update_post_meta( $order->id, 'Additional info', wc_clean( print_r( $posted['additional_information'], true) ) );
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
