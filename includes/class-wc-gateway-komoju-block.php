<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

class WC_Gateway_Komoju_Blocks extends AbstractPaymentMethodType {

    private $gateway;
    protected $name = 'komoju'; // I think this is the id of WC_Gateway_Komoju

    public function initialize() {
        require_once dirname(__DIR__) . '/class-wc-gateway-komoju.php';
        $this->gateway = new WC_Gateway_Komoju();
        $this->settings = $this->gateway->settings;
        // $this->settings = get_option('woocommerce_komoju_settings', []);
    }

    public function is_active() {
        error_log('WC_Gateway_Komoju_Blocks is_available: ' . print_r($this->gateway->is_available(), true));
        // return $this->gateway->is_available(); // Maybe we can get this from the gateway settings?
        return true;
    }

    // Not sure what to do about this. We don't have js files to load directly...
    public function get_payment_method_script_handles() {
        if (!is_checkout()) {
            return [];
        }

        $komoju_fields_js = get_option('komoju_woocommerce_fields_url');
        // Is this where we store our checkout js?
        if (!$komoju_fields_js) {
            $komoju_fields_js = 'https://multipay.komoju.com/fields.js';
        }

        // wp_enqueue_script('komoju-fields', $komoju_fields_js);

        wp_register_script(
            'WC_Gateway_Komoju-blocks-integration',
            $komoju_fields_js,
            [
                'wc-blocks-registry',
                'wc-settings',
                'wp-element',
                'wp-html-entities',
                'wp-i18n',
            ],
            null,
            true
        );
        // if( function_exists( 'wp_set_script_translations' ) ) {            
        //     wp_set_script_translations( 'wc_gateway_komoju-blocks-integration');
            
        // }
        return [ 'WC_Gateway_Komoju-blocks-integration' ];
    }

    public function get_payment_method_data() {
        error_log('in get_payment_method_data');
        return [
            'title' => $this->gateway->title,
            //'description' => $this->gateway->description,
        ];
    }
}
?>