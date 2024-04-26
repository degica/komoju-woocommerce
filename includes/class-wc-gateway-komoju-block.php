<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class WC_Gateway_Komoju_Blocks extends AbstractPaymentMethodType
{
	private $gateway;
	protected $name;

	public function __construct(WC_Gateway_Komoju_Single_Slug $gateway)
	{
		$this->gateway = $gateway;
		$this->name = $gateway->id;
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
			plugin_dir_url(__FILE__) . '../frontend.js',
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
			'title' => $this->gateway->title,
			'description' => $this->gateway->method_description,
			'id' => $this->name,
		];
	}
}
?>