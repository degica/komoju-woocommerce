<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings for Komoju Gateway (hosted page)
 */

return array(
	'enabled' => array(
		'title'   => __( 'Enable/Disable', 'komoju-woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable Komoju', 'komoju-woocommerce' ),
		'default' => 'no'
	),
	'title' => array(
		'title'       => __( 'Title', 'komoju-woocommerce' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'komoju-woocommerce' ),
		'default'     => __( 'Komoju', 'komoju-woocommerce' ),
		'desc_tip'    => true,
	),
	'description' => array(
		'title'       => __( 'Description', 'komoju-woocommerce' ),
		'type'        => 'textarea',
		'description' => __( 'Payment method description that the customer will see on your checkout.', 'komoju-woocommerce' ),
		'default'     => __( 'Make your payment through Komoju: offline and online Japanese payments like Konbini, credit cards, WebMoney, ...', 'komoju-woocommerce' ),
		'desc_tip'    => true,
	),
	'supported_methods' => array(
		'title'	=> __('Supported methods set in Komoju', 'komoju-woocommerce'),
		'type'	=> 'title',
		'id'	=> 'supported-methods-in-komoju'
	),
	'credit_card' => array(
		'title'   => __( 'Credit Card', 'komoju-woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow credit card', 'komoju-woocommerce' ),
		'default' => 'yes'
	),
	'web_money' => array(
		'title'   => __( 'Web Money', 'komoju-woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow Web Money', 'komoju-woocommerce' ),
		'default' => 'yes'
	),
	'konbini' => array(
		'title'   => __( 'Konbini', 'komoju-woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow delayed payment in convenience store', 'komoju-woocommerce' ),
		'description' => __( 'Lawson, Family Mart, Sunkus, Circle-K, Ministop, Daily Yamazaki, 7-Eleven', 'komoju-woocommerce' ),
		'desc_tip'    => true,
		'default' => 'yes'
	),
	'bank_transfer' => array(
		'title'   => __( 'Bank Transfer', 'komoju-woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow bank transfer', 'komoju-woocommerce' ),
		'default' => 'yes'
	),
	'pay_easy' => array(
		'title'   => __( 'Pay Easy', 'komoju-woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow delayed payment through Pay Easy', 'komoju-woocommerce' ),
		'default' => 'yes'
	),
	'API_settings' => array(
		'title'	=> 'API Settings',
		'type'	=> 'title',
		'id'	=> 'api-seetings-in-komoju'
	),
	'accountID' => array(
		'title'       => __( 'Komoju merchant ID', 'komoju-woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Please enter your Komoju account ID.', 'komoju-woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
	),
	'secretKey' => array(
		'title'       => __( 'Secret Key from Komoju', 'komoju-woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Please enter your Komoju secret key.', 'komoju-woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
	),
	'callbackURL' => array(
		'title'       => __( 'Callback Url', 'komoju-woocommerce' ),
		'type'        => 'text',
		'description' => sprintf( __( 'Specify a special callback url (or leave this field empty if you don\'t know what it is). Default url is %s', 'komoju-woocommerce' ), $this->get_mydefault_api_url() ),
		'default'     => '',
	),
	'invoice_prefix' => array(
		'title'       => __( 'Invoice Prefix', 'komoju-woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Please enter a prefix for your invoice numbers. If you use your Komoju account for multiple stores ensure this prefix is unique.', 'komoju-woocommerce' ),
		'default'     => 'WC-',
		'desc_tip'    => true,
	),
	'debug' => array(
		'title'       => __( 'Debug Log', 'komoju-woocommerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'komoju-woocommerce' ),
		'default'     => 'no',
		'description' => sprintf( __( 'Log Komoju events inside <code>%s</code>', 'komoju-woocommerce' ), wc_get_log_file_path( 'komoju' ) )
	),
);
?>