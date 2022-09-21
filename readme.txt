=== KOMOJU Payments ===
Contributors: degica
Tags: WooCommerce,Payment Gateway,Komoju
Requires at least: 5.3
Tested up to: 5.7
Stable tag: trunk
Requires PHP: 7.2
WC requires at least: 3.0.0
WC tested up to: 5.2.0
License: MIT
License URI: https://directory.fsf.org/wiki/License:X11

== Description ==

## KOMOJU Global Payment Gateway

**Expand your business to global markets and accept payments as a local!**

Give your customers a local payment experience they expect. Increase conversion rates with our local payment methods such as Alipay, Credit Cards (South-Korea & Japan), GrabPay (Singapore), Konbini (Japan), POLi (Australia) and many more!

If you're expanding your business globally and need a payment partner with local experience, [KOMOJU](https://en.komoju.com/) is right for you. Adding the payment methods your customer expects is key to success and will drastically decrease abandon rates and increase conversion and sales. Give your customers a local payment experience, in the local currency they are familiar with and with their preferred payment method.

[KOMOJU](https://en.komoju.com/) integrates seamlessly with your checkout and all payment options are displayed dynamically. This means you do not have to make any additional configuration after setting up our plugin.

### Features

KOMOJU lets you accept payments in multiple currencies through a variety of local payment methods and credit/debit cards. We cover payment methods in Japan, South-Korea, China, South-East Asia, Australia and Europe!

- **Clear pricing**: You only pay per transaction - no hidden fees, charges or setup costs!
- **Fast Payout**: Choose between a monthly or weekly cycle - you'll get your funds quickly.
- **Multi-Currency:** Accept payments in local currency, and receive your funds in your preferred currency. We support USD, EUR, JPY, SGD, HKD and AUD for payouts.
- **Easy onboarding:** We quickly review every merchant and can provide payments fast.
- **Many payment methods accepted**: We provide payment methods across 6 regions, with most payment methods covered.

### Benefits

When enabling KOMOJU Payment Gateway on your store, you will be able to accept payments from your customers, worldwide. Your customers will pay in their preferred currency and payment method.

Our plugin is fully PCI-DSS compliant, so you do not need to worry about regulations and cardholder safety. With 3D-Secure 2.0, you can be rest assured that your payments are secured and authenticated.

You can accept payments with digital wallets such as Alipay, WeChat Pay, PayPay, LINE Pay, Toss, PAYCO, GrabPay, Doku, OVO and many more. Payments with digital wallets give your customers a customised, trusted and convenient payment option.

We support businesses that are conducting business cross-border. For the majority of the payment methods we do not require a local entity or bank account. Accept payments wherever you're located, and ship your products worldwide.

**KOMOJU is the ideal way to accept payments on your store to ensure your customers can pay without friction with the payment methods they want.**

### Accepted Payment Methods

We currently accept the following payment methods:

**Japan**

- Credit / Debit Cards Japan (Visa, Mastercard, American Express, JCB, Diners Club)
- Convenience Store (Konbini)
- Pay-Easy
- Japan Bank Transfer
- PayPay
- LINE Pay
- MerPay
- RakutenPay
- Paidy
- Carrier Billing (NTT Docomo, Softbank, au)
- Prepaid Vouchers (WebMoney, BitCash, NET Cash)

**South-Korea**

- Credit / Debit Cards (Samsung, Lotte, Hyundai, Hana, BC, NH, Shinhan, KB)
- Toss
- Payco

**China**

- Alipay
- WeChat Pay
- UnionPay

**South-East Asia**

- Doku
- OVO
- eNETS
- GrabPay
- Dragonpay
- FPX

**Australia**

- POLi

**Europe**

- iDEAL
- Bancontact
- Multibanco
- EPS
- Giropay
- Przelewy24
- BLIK
- MyBank
- Sofort

== Installation ==

1. Upload the plugin to your server where wordpress and WooCommerce are installed via FTP or other file transfer method to the wordpress/wp-content/plugins directory
1. Change to the plugin directory
1. Run: `unzip woocommerce-komoju.zip`

Login to Wordpress as Administrator

Click on the 'Plugins' menu on the left hand side.

You should see the WooCommerce Komoju Gateway listed among the plugins. Click the Activate Link for this gateway.
Next you need to configure the Plugin in WooCommerce. To do so, from the left hand menu, select 'WooCommerce' and then 'Settings'.

Then click 'Checkout' from the Top Menu.

Click on the 'Komoju' Link just below the top tabbed menu.

Click the Enable/Disable Box to enable this gateway.

Enter your KOMOJU API credentials in this configuration page. The information to set here is defined in your KOMOJU Merchant Settings page. Ignore the "Webhook Secret Token" for now, as it will be filled out after your Komoju account has been configured.
The value for "Komoju merchant ID" field is the Merchant UUID value on the Merchant Settings page.
The value for the "Secret Key from Komoju" field is the Secret Key value on the Merchant Settings page.
Make sure they match.
Always start the configuration by using the test mode secret key and do a few tests before going live.

To enable Debug click the 'Enable logging' box.

= Configuring your Komoju account =
To ensure that the WooCommerce plugin works correctly you will need to set up a webhook from your KOMOJU dashboard to the wordpress instance. To do this you will need to go to your [Webhook page on the Komoju dashboard](https://komoju.com/admin/webhooks) and click "New Webhook". If you don't know what the webhook URL should be you can check the admin page for this plugin on your wordpress instance to see the default address. The secret can be anything you want (as long as you remember it), but you must make sure the following events are ticked:

- payment.authorized
- payment.captured
- payment.expired
- payment.cancelled
- payment.refunded

Ensure that the "Active" checkbox is also ticked and then click "Create Webhook".

Go back to your Wordpress instance and set the "Webhook Secret Token" value on the Komoju Woocommerce plugin to be the same as the secret set for the webhook.

== Frequently Asked Questions ==

= What versions of WooCommerce is this compatible with? =

At the moment, this plugin has been tested and is known to work up to version
4.0.1. If you are using a later version, please contact us regarding this.

= Where can I get more information? =

Please contact contact@komoju.com if you have any questions about
the installation of the module.

== Changelog ==

= 1.0 =
Initial release for the Wordpress store.

= 1.0.1 =
Fix issue preventing coupon discounts from being applied at checkout.

= 1.1.0 =
Available payment methods on checkout will now match those available on the user's KOMOJU account.
Removed settings related to payment methods. Payment Methods should now be managed directly through KOMOJU.
Removed payment method icons on checkout (To be re-added at a later date)

= 2.0 =
Introduced new hosted checkout design
Added option to use 'on-hold' status for authorized payments

= 2.1.0 =
Added filter 'woocommerce_komoju_payment_methods' to allow users to change the list of offered payment methods to their users.

= 2.1.1 =
Update Plugin Name

= 2.2.0 =
Users can now select individual payment methods to be exposed as WooCommerce payment gateways.
This should provide better compatibility with other plugins that filter or otherwise interact with payment gateways.

= 2.2.3 =
Fixed issue with orders that don't have an email address.

= 2.2.4 =
Fixed issue where some stores saw errors on the settings page after filling in their secret key.

= 2.2.5 =
Fixed problem where some installs were not generating order IDs correctly.

= 2.2.6 =
Fixed issue where new versions were not being registered automatically.

= 2.2.7 =
Fixed issue with stores that don't produce a customer name.

= 2.3.0 =
Introduced quick-setup, removing the need to copy/paste values from KOMOJU.
Removed currency restriction, allowing the plugin to be used with any store currency.

= 2.3.1 =
Fixed cents conversion problem with currencies that use decimal points.

= 2.4.0 =
Refunding KOMOJU payments through the WooCommerce dashboard is now supported.
Added a link to the KOMOJU admin page for orders paid with KOMOJU.
Clicking "back to merchant" on KOMOJU will now take you to the pay-order page instead of checkout.
Can now toggle whether or not KOMOJU payment method icons appear.

= 2.4.1 =
Fixed bug where plugin would ignore locale strings that include a country code.

= 2.5.0 =
Add 'komoju_session_return_url' filter.

= 2.6.0 =
Optionally perform order completion in the background.

= 2.6.1 =
Fix webhooks with currencies that use cents.

= 2.6.2 =
Swap first/last name order when sending to KOMOJU (KOMOJU expects given before family).

= 2.6.3 =
Fix problem where komoju payment gateways were not added in some situations.
