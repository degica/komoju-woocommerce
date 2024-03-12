<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

class WC_Gateway_Komoju_Blocks extends AbstractPaymentMethodType {
    protected $payment_method;
    protected $name;
    
    public function __construct($payment_method) {
        $this->payment_method = $payment_method;
        $this->name = $payment_method->id;
    }
    
    public function initialize() {
        $this->settings = $this->payment_method->settings;
        // $this->settings = $this->gateway->settings;
        // $this->settings = get_option('woocommerce_komoju_settings', []);

        // error_log('WC_Gateway_Komoju_Blocks initialize gateway: ' . print_r($this->gateway, true));
    }

    public function is_active() {
        // error_log('WC_Gateway_Komoju_Blocks is_available: ' . print_r($this->gateway->is_available(), true));
        return $this->settings['enabled'];
    }

    // Not sure what to do about this. We don't have js files to load directly...
    public function get_payment_method_script_handles() {
        return [];
        // if (!is_checkout()) {
        //     return [];
        // }

        // $komoju_fields_js = get_option('komoju_woocommerce_fields_url');
        // // Is this where we store our checkout js?
        // if (!$komoju_fields_js) {
        //     $komoju_fields_js = 'https://multipay.komoju.com/fields.js';
        // }

        // // wp_enqueue_script('komoju-fields', $komoju_fields_js);

        // wp_register_script(
        //     'WC_Gateway_Komoju-blocks-integration',
        //     $komoju_fields_js,
        //     [
        //         'wc-blocks-registry',
        //         'wc-settings',
        //         'wp-element',
        //         'wp-html-entities',
        //         'wp-i18n',
        //     ],
        //     null,
        //     true
        // );
        // // if( function_exists( 'wp_set_script_translations' ) ) {            
        // //     wp_set_script_translations( 'wc_gateway_komoju-blocks-integration');
            
        // // }
        // return [ 'WC_Gateway_Komoju-blocks-integration' ];
    }

    public function get_payment_method_data() {
        // error_log('WC_Gateway_Komoju_Blocks get_payment_method_data payment_method->method_description: ' . print_r($this->payment_method->method_description, true));
        return [
            'title' => $this->name,
            'description' => $this->payment_method->method_description
        ];
    }
}
?>