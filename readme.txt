=== Komoju ===
Contributors: degica
Tags: WooCommerce,Payment Gateway,Komoju
Requires at least: 5.3
Tested up to: 5.4
Stable tag: trunk
Requires PHP: 7.2
License: MIT
License URI: https://directory.fsf.org/wiki/License:X11

Built for developers, KOMOJU is the easiest way to integrate Japanese payments into your WooCommerce store.

== Description ==

The Komoju Payments WooCommerce plugin allows customers to checkout with a variety of Japanese payment options, including Credit Card, Konbini, Webmoney and more.

To use this plugin you must have a [Komoju account](https://komoju.com/).

= Configuration Instructions =
Login to Wordpress as Administrator 

Click on the 'Plugins' menu on the left hand side.

You should see the WooCommerce Komoju Gateway listed among the plugins. Click the Activate Link for this gateway.
Next you need to configure the Plugin in WooCommerce. To do so, from the left hand menu, select 'WooCommerce' and then 'Settings'.

Then click 'Checkout' from the Top Menu.

Click on the 'Komoju' Link just below the top tabbed menu.

Click the Enable/Disable Box to enable this gateway.

Enter your Komoju API credentials in this configuration page. The information to set here is defined in your Komoju Merchant Settings page. Ignore the "Webhook Secret Token" for now, as it will be filled out after your Komoju account has been configured.
The value for "Komoju merchant ID" field is the Merchant UUID value on the Merchant Settings page.
The value for the "Secret Key from Komoju" field is the Secret Key value on the Merchant Settings page. 
Make sure they match.
Always start the configuration by using the test mode secret key and do a few tests before going live.

To enable Debug click the 'Enable logging' box.

= Configuring your Komoju account =
To ensure that the WooCommerce plugin works correctly you will need to set up a webhook from your Komoju dashboard to the wordpress instance. To do this you will need to go to your [Webhook page on the Komoju dashboard](https://komoju.com/admin/webhooks) and click "New Webhook". If you don't know what the webhook URL should be you can check the admin page for this plugin on your wordpress instance to see the default address. The secret can be anything you want (as long as you remember it), but you must make sure the following events are ticked:

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

Please contact contact@degica.com if you have any questions about
the installation of the module.

== Changelog ==

= 1.0 =
Initial release for the Wordpress store.