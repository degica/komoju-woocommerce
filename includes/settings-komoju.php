<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings for Komoju Gateway (hosted page)
 */

return array(
	'enabled' => array(
		'title'   => __( 'Enable/Disable', 'woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable Komoju', 'woocommerce' ),
		'default' => 'no'
	),
	'title' => array(
		'title'       => __( 'Title', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
		'default'     => __( 'Komoju', 'woocommerce' ),
		'desc_tip'    => true,
	),
	'description' => array(
		'title'       => __( 'Description', 'woocommerce' ),
		'type'        => 'textarea',
		'description' => __( 'Payment method description that the customer will see on your checkout.', 'woocommerce' ),
		'default'     => __( 'Make your payment through Komoju: offline and online Japanese payments like Konbini, credit cards, WebMoney, ...', 'woocommerce' ),
		'desc_tip'    => true,
	),
	'supported_methods' => array(
		'title'	=> 'Supported methods set in Komoju',
		'type'	=> 'title',
		'id'	=> 'supported-methods-in-komoju'
	),
	'credit_card' => array(
		'title'   => __( 'Credit Card', 'woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow credit card', 'woocommerce' ),
		'default' => 'yes'
	),
	'web_money' => array(
		'title'   => __( 'Web Money', 'woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow Web Money', 'woocommerce' ),
		'default' => 'yes'
	),
	'konbini' => array(
		'title'   => __( 'Convenient store', 'woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow delayed payment in convenient store', 'woocommerce' ),
		'description' => __( 'Lawson, Family Mart, Sunkus, Circle-K, Ministop, Daily Yamazaki, 7-Eleven', 'woocommerce' ),
		'desc_tip'    => true,
		'default' => 'yes'
	),
	'bank_transfer' => array(
		'title'   => __( 'Bank Transfer', 'woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow bank transfer', 'woocommerce' ),
		'default' => 'yes'
	),
	'pay_easy' => array(
		'title'   => __( 'Pay Easy', 'woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow delayed payment through Pay Easy', 'woocommerce' ),
		'default' => 'yes'
	),
	'API_settings' => array(
		'title'	=> 'API Settings',
		'type'	=> 'title',
		'id'	=> 'api-seetings-in-komoju'
	),
	'accountID' => array(
		'title'       => __( 'Komoju merchant ID', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Please enter your Komoju account ID.', 'woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
	),
	'secretKey' => array(
		'title'       => __( 'Secret Key from Komoju', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Please enter your Komoju secret key.', 'woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
	),
	'callbackURL' => array(
		'title'       => __( 'Callback Url', 'woocommerce' ),
		'type'        => 'text',
		'description' => sprintf( __( 'Specify a special callback url (or leave this field empty if you don\'t know what it is). Default url is %s', 'woocommerce' ), $this->get_mydefault_api_url() ),
		'default'     => '',
	),
	'testmode' => array(
		'title'       => __( 'Komoju Sandbox', 'woocommerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable Komoju sandbox', 'woocommerce' ),
		'default'     => 'yes',
		'description' => sprintf( __( 'When checked, your Komoju sandbox will be used in order to test payments. Sign up for a developer account <a href="%s">here</a>.', 'woocommerce' ), 'https://sandbox.komoju.com/sign_up' ),
	),
	'invoice_prefix' => array(
		'title'       => __( 'Invoice Prefix', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Please enter a prefix for your invoice numbers. If you use your Komoju account for multiple stores ensure this prefix is unique.', 'woocommerce' ),
		'default'     => 'WC-',
		'desc_tip'    => true,
	),
	'debug' => array(
		'title'       => __( 'Debug Log', 'woocommerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'woocommerce' ),
		'default'     => 'no',
		'description' => sprintf( __( 'Log Komoju events inside <code>%s</code>', 'woocommerce' ), wc_get_log_file_path( 'komoju' ) )
	),
);
?>