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
    }

    public function is_active() {
        return $this->settings['enabled'];
    }

    // We enqueue the komoju-fields js in woocommerce_komoju_load_scripts in index.php. Register
    // the script here.
    public function get_payment_method_script_handles() {
        $komoju_fields_js = plugins_url('js/komoju-fields-blocks.js', __FILE__);
        wp_register_script('komoju-fields-blocks', $komoju_fields_js, [], null, true);
    
        return ['komoju-fields-blocks'];
    }

    public function get_payment_method_data() {
        // Trying to pass the html from WC_Gateway_Komoju_Single_Slug::payment_fields to the
        // frontend, without success.
        ob_start();
        $this->payment_method->payment_fields();
        $paymentFields = ob_get_clean();
   
        return [
            'title' => $this->name,
            'description' => $this->payment_method->method_description,
            'supports' => array_filter($this->payment_method->supports, array($this->payment_method, 'supports')),
            'paymentFields' => $paymentFields,
            // 'blocks' => [
            //     'editor' => 'woocommerce/checkout',
            //     'frontend' => 'woocommerce/checkout',
            // ],
        ];
    }
}
?>