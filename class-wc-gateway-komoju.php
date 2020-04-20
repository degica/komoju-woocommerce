<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 *Komoju Payment Gateway
 *
 * Provides access to Japanese local payment methods.
 *
 * @class       WC_Gateway_Komoju
 * @extends     WC_Payment_Gateway
 * @version     0.1
 * @package     WooCommerce/Classes/Payment
 * @author      WooThemes
 */
class WC_Gateway_Komoju extends WC_Payment_Gateway {

	/** @var array Array of locales */
	public $locale;

	/** @var boolean Whether or not logging is enabled */
	public static $log_enabled = false;

	/** @var WC_Logger Logger instance */
	public static $log = false;

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {

		$this->id                	= 'komoju';
		$this->icon              	= apply_filters('woocommerce_komoju_icon', plugins_url('assets/images/komoju-logo.png', __FILE__));
		$this->has_fields         	= true;
		$this->method_title       	= __( 'Komoju', 'komoju-woocommerce' );
		$this->method_description 	= __( 'Allows payments by Komoju, dedicated to Japanese online and offline payment gateways.', 'komoju-woocommerce' );
		$this->debug          		= 'yes' === $this->get_option( 'debug', 'yes' );
		$this->invoice_prefix		= $this->get_option( 'invoice_prefix' );
        $this->accountID     		= $this->get_option( 'accountID' );
        $this->secretKey     		= $this->get_option( 'secretKey' );
		$this->webhookSecretToken   = $this->get_option( 'webhookSecretToken' );
		// supported payment gateways chosen by the merchant (among the ones Komoju is providing)
		$this->credit_card			= $this->get_option( 'credit_card' );
		$this->web_money			= $this->get_option( 'web_money' );
		$this->konbini				= $this->get_option( 'konbini' );
		$this->bank_transfer		= $this->get_option( 'bank_transfer' );
		$this->pay_easy				= $this->get_option( 'pay_easy' );
		self::$log_enabled    		= $this->debug;
		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();
		// Define user set variables
		$this->title        = $this->get_option( 'title' );
		$this->description  = $this->get_option( 'description' );
		$this->instructions = $this->get_option( 'instructions', $this->description );
		// Filters
		// Actions
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		if ( ! $this->is_valid_for_use() ) {
			$this->enabled = 'no';
			WC_Gateway_Komoju::log( 'is not valid for use. No IPN set.' );
		} else {
			include_once( 'includes/class-wc-gateway-komoju-ipn-handler.php' );
			new WC_Gateway_Komoju_IPN_Handler( $this->webhookSecretToken, $this->secretKey, $this->invoice_prefix );
		}

	}

	/**
	 * Logging method
	 * @param  string $message
	 */
	public static function log( $message ) {
		if ( self::$log_enabled ) {
			if ( empty( self::$log ) ) {
				self::$log = new WC_Logger();
			}
			self::$log->add( 'komoju', $message );
		}
	}

	/**
	 * Check if this gateway is enabled and available in the user's country
	 *
	 * @return bool
	 */
	public function is_valid_for_use() {
		return in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_komoju_supported_currencies', array( 'JPY' ) ) );
	}

	/**
	 * Admin Panel Options
	 */
	public function admin_options() {
		if ( $this->is_valid_for_use() ) {
			parent::admin_options();
		} else {
			?>
			<div class="inline error"><p><strong><?php _e( 'Gateway Disabled', 'komoju-woocommerce' ); ?></strong>: <?php _e( 'Komoju does not support your store currency.', 'komoju-woocommerce' ); ?></p></div>
			<?php
		}
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields = include( 'includes/settings-komoju.php' );
	}

	/**
	 * Process the payment and return the result
	 *
	 * @param int $order_id
	 * @return array
	 */
	public function process_payment( $order_id ) {
		include_once( 'includes/class-wc-gateway-komoju-request.php' );
		$order          = wc_get_order( $order_id );
		$komoju_request = new WC_Gateway_Komoju_Request( $this );
		return array(
			'result'   => 'success',
			'redirect' => $komoju_request->get_request_url( $order, $_POST['komoju-method'] ) 
		);
	}


	/**
	 * Payment form on checkout page
	 */
	public function payment_fields() {

        if ( $description = $this->get_description() ) {
            echo wpautop( wptexturize( $description ) );
        }

		$this->komoju_method_form();
    }

    /**
     * Form to choose the payment method within Komoju optional gateways
     */
    private function komoju_method_form(  $args = array(), $fields = array()  ) {

        $default_args = array(
            'fields_have_names' => true,
        );

        $args = wp_parse_args( $args, apply_filters( 'woocommerce_komoju_method_form_args', $default_args, $this->id ) );

		$str = '<p class="form-row form-row-wide validate-required woocommerce-validated"><label for="' . esc_attr( $this->id ) . '-method">' . __( 'Method of payment:', 'komoju-woocommerce' ) . ' <abbr class="required" title="required">*</abbr></label>';
		if ( 'yes' == $this->credit_card )
            $str .= '<input id="' . esc_attr( $this->id ) . '-method" class="input-radio" type="radio" value="credit_card" name="' . ( $args['fields_have_names'] ? $this->id . '-method' : '' ) . '" /> '. __( 'Credit Card', 'komoju-woocommerce' ).'<img src="'.plugins_url('assets/images/cards.png', __FILE__).'" /><br/>';
		if ( 'yes' == $this->konbini )
			$str .= '<input id="' . esc_attr( $this->id ) . '-method" class="input-radio" type="radio" value="konbini" name="' . ( $args['fields_have_names'] ? $this->id . '-method' : '' ) . '" /> '. __( 'Konbini', 'komoju-woocommerce' ).'<img src="'.plugins_url('assets/images/konbini.png', __FILE__).'" /><br/>';
		if ( 'yes' == $this->web_money )
            $str .= '<input id="' . esc_attr( $this->id ) . '-method" class="input-radio" type="radio" value="web_money" name="' . ( $args['fields_have_names'] ? $this->id . '-method' : '' ) . '" /> '. __( 'WebMoney', 'komoju-woocommerce' ).'<img src="'.plugins_url('assets/images/webmoney.png', __FILE__).'" /><br/>';
		if ( 'yes' == $this->bank_transfer )
            $str .= '<input id="' . esc_attr( $this->id ) . '-method" class="input-radio" type="radio" value="bank_transfer" name="' . ( $args['fields_have_names'] ? $this->id . '-method' : '' ) . '" /> '. __( 'Bank Transfer', 'komoju-woocommerce' ).'<br/>';
		if ( 'yes' == $this->pay_easy )
            $str .= '<input id="' . esc_attr( $this->id ) . '-method" class="input-radio" type="radio" value="pay_easy" name="' . ( $args['fields_have_names'] ? $this->id . '-method' : '' ) . '" /> '. __( 'Pay Easy', 'komoju-woocommerce' ).'<img src="'.plugins_url('assets/images/payeasy.png', __FILE__).'" /><br/>';
		$str .= '</p>';
        $default_fields = array( 'method-field' => $str );
        $fields = wp_parse_args( $fields, apply_filters( 'woocommerce_komoju_method_form_fields', $default_fields, $this->id ) );
        ?>
        <fieldset id="<?php echo $this->id; ?>-cc-form">
            <?php do_action( 'woocommerce_komoju_method_form_start', $this->id ); ?>
            <?php
                foreach ( $fields as $field ) {
                    echo $field;
                }
            ?>
            <?php do_action( 'woocommerce_komoju_method_form_end', $this->id ); ?>
            <div class="clear"></div>
        </fieldset>
        <?php
    }	

	private function get_mydefault_api_url(){
		// In dev the relative plugin URL will remove the host name, but it
		// will appear in production instances
		return WC()->api_request_url( 'WC_Gateway_Komoju' );
	}

	/**
     * Validate the payment form (for custom fields added)
     */
	function validate_fields() {
		if ( !isset( $_POST['komoju-method'] ) ){
			wc_add_notice( __( 'Please select a payment method (how you want to pay)', 'komoju-woocommerce' ), 'error' );
			return false;
		}
		return true;
	}
	
}
