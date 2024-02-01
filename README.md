## KOMOJU Global Payment Gateway

**Expand your business to global markets and accept payments as a local!**

Give your customers a local payment experience they expect. Increase conversion rates with our local payment methods such as Alipay, Credit Cards (South-Korea & Japan), GrabPay (Singapore), Konbini (Japan) and many more!

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
- GrabPay
- Dragonpay
- FPX

**Europe**

- Bancontact
- Multibanco
- EPS
- Giropay
- Przelewy24
- BLIK
- MyBank
- Sofort

## Overview

Once this plugin has been installed, the module can be configured through the
WooCommerce payment section.

Enable the payment gateway and configure the account as described in the
'Installation' section.

The module should first be set to test mode (sandbox).

Note - an account with [Komoju](https://komoju.com) is required before using this module.

## Installation

Upload the contents of this repository to your server where wordpress and WooCommerce are installed via FTP or other file transfer method to the wordpress/wp-content/plugins directory

The [zip file](https://github.com/komoju/komoju-woocommerce/archive/master.zip) needs to be unzipped as follows :

```
cd wordpress/wp-content/plugins
unzip woocommerce-komoju.zip
```

Login to Wordpress as Administrator

Click on the 'Plugins' menu on the left hand side.

You should see the WooCommerce Komoju Gateway listed among the plugins list. Click the Activate Link
for this gateway.
Next you need to configure the Plugin in WooCommerce. To do so, from the left hand menu,
select 'WooCommerce' and then 'Settings'.

Then click 'Checkout' from the Top Menu.

Click on the 'Komoju' Link just below the top tabbed menu.

Click the Enable/Disable Box to enable this gateway.

Enter your Komoju API credentials in this configuration page. Those data to set here are the ones defined in your Komoju dashboard. Ignore the "Webhook Secret Token" for now, as it will be filled out after your Komoju account has been configured.
Make sure they match.
Always start the configuration by using the test mode secret key and do a few tests before going live.

To enable Debug click the 'Enable logging' box.


### Configuring your Komoju account

To ensure that the WooCommerce plugin works correctly you will need to set up a webhook from your Komoju dashboard to the wordpress instance. To do this you will need to go to your [Webhook page on the Komoju dashboard](https://komoju.com/admin/webhooks) and click "New Webhook". If you don't know what the webhook URL should be you can check the admin page for this plugin on your wordpress instance to see the default address. The secret can be anything you want (as long as you remember it), but you must make sure the following events are ticked:

- payment.authorized
- payment.captured
- payment.expired
- payment.cancelled
- payment.refunded

Ensure that the "Active" checkbox is also ticked and then click "Create Webhook".

Go back to your Wordpress instance and set the "Webhook Secret Token" value on the Komoju Woocommerce plugin to be the same as the secret set for the webhook.

## Frequently asked questions

### What versions of WooCommerce is this compatible with?

At the moment, this plugin has been tested and is known to work up to version
6.3.1. If you are using a later version, please contact us regarding this.

### Where can I get more information?

Please contact contact@komoju.com if you have any questions about
the installation of the module. If you would like to set up an account to use with this plugin, please sign up at https://www.komoju.com.

## License

Komoju WooCommerce plugin copyright © 2020 by Degica Ltd.

This is a free plugin for use by Komoju customers. This code is not be traded or sold for profit.

## Development

### Testing Locally

Refer to the [Dev setup guide](./docs/dev_setup.md) for a step by step process for configuring WordPress, WooCommerce and the Komoju plugin.

### Switching languages

To switch the WordPress instance to Japanese you can specify the language when starting the containers:

```bash
$ WPLANG=ja docker-compose up
```

**Note:** This won't switch the language for the rest of the WordPress instance, but it will set the language for the plugin config page.

**Note:** Due to how the Wordpress config is created the language switch will only take effect the first time you build the containers. Afterwards you will need to manually edit the wp-config.php:

```bash
$ docker-compose exec web bin/bash
root@42a884a66e73:/var/www/html# vi wp-config.php
# replace the: define( 'WPLANG' , 'en_US' ); with the desired language, ja for Japanese or en_US for English.
```

### Translations

To create a pot file from source code, execute the following command:

```
docker-compose exec web /bin/bash # login to running container
cd wp-content/plugins/komoju-woocommerce # go to plugin directory
./bin/create_pot_file # ./languages/komoju-commerce.pot will be generated
```

Copy the generated pot file and name it `komoju-commerce-ja.po` to create po file and translate all messages in it.

To create mo file from a po file, execute the following command:

```
./bin/create_mo_file
```

You need to execute it every time after updating po files.

#### Updating the existing po files

Rather than having to copy and paste the existing translations across to the new pot file, you can use [poedit](https://poedit.net/download). After generating the POT file as above, open the `komoju-woocommerce-ja.po` file in Poedit, then go to Catalogue->Update from POT File, to automatically update the existing Japanese translations with the new schema.

#### Updating plugin store page content

WordPress provides a [readme template](https://wordpress.org/plugins/readme.txt)
used to generate the plugin's page on the WordPress Store. We are using a third party
Github action](https://github.com/10up/action-wordpress-plugin-deploy) to do this.
See our [docs](https://github.com/degica/komoju-woocommerce/blob/master/docs/uploading_to_wordpress_store.md)
for uploading newer versions of the plugin to the WordPress store.
