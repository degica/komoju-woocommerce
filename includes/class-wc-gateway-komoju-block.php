<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class WC_Gateway_Komoju_Blocks extends AbstractPaymentMethodType
{
	private $gateway;
	protected $name;
	protected $settings;

	public function __construct(WC_Gateway_Komoju_Single_Slug $gateway)
	{
		$this->gateway = $gateway;
		$this->name = $gateway->id;
		$this->settings = $gateway->payment_method->settings;
	}

	public function initialize()
	{
		$this->settings = get_option('woocommerce_test_komoju_settings', []);
	}

	public function is_active()
	{
		return $this->gateway->is_available();
	}

	public function get_payment_method_script_handles()
	{
		wp_register_script(
			'komoju-payment-blocks-integration',
			plugin_dir_url(__FILE__) . './js/komoju-checkout-blocks.js',
			[
				'wc-blocks-registry',
				'wc-settings',
				'wp-element',
				'wp-html-entities',
			],
			null,
			true
		);

		return ['komoju-payment-blocks-integration'];
	}

	public function get_payment_method_data()
	{
		return [
			'id' => $this->name,
			'title' => $this->gateway->title,
			'description' => $this->gateway->method_description,
			'supports' => array_filter($this->gateway->supports, array($this->gateway, 'supports')),
			// 'paymentFields' => $this->gateway->payment_fields()
		];
	}
}
?>