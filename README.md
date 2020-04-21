## Komoju for WooCommerce.

Japanese payments for WooCommerce.
This plugin supports the following Japanese payment methods:

* Credit Card (クレジットカード)
* Convenience Store (コンビニ)
* PayEasy (ペイジー)
* WebMoney (ウェブマネー)
* Bank Transfer (銀行振込)

## Overview

Once this has been installed, the module can be configured through the
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
- payment.failed

Ensure that the "Active" checkbox is also ticked and then click "Create Webhook". 

Go back to your Wordpress instance and set the "Webhook Secret Token" value on the Komoju Woocommerce plugin to be the same as the secret set for the webhook.

## Frequently asked questions

### What versions of WooCommerce is this compatible with?

At the moment, this plugin has been tested and is known to work up to version
4.0.1. If you are using a later version, please contact us regarding this.

### Where can I get more information?

Please contact support@degica.com if you have any questions about
the installation of the module. If you would like to setup an account to use
with this module, please contact https://www.degica.com/contact/

## License

Komoju WooCommerce plugin copyright © 2020 by Degica Ltd.

This is a free plugin for use by Komoju customers. This code is not be traded or sold for profit.

## Development

### Testing Locally

To test locally you can start wordpress in Docker:
```bash
$ docker-compose up
```

This will start the Wordpress instance on http://127.0.0.1 (Note: It **has** to be 127.0.0.1, `localhost` will not work). Once the WordPress site is configured, activate the WooCommerce plugin, the Relative URLs plugin and the Komoju WooCommerce plugin, then configure them as directed in the Installation section above.

Because the Komoju plugin uses webhooks to receive notifications once the payment is complete the wordpress instance will need to be accessible to the internet. This can be done with [ngrok](https://ngrok.com/):

```
ngrok http -host-header=rewrite http://127.0.0.1:8000
```

This will create a tunnel from the address ngrok gives you to the WordPress site. In the Komoju dashboard make sure to configure the webhook to point the the ngrok address. **Note:** This only seems to work with FireFox, Chrome seems to time out trying to access the WordPress site through the ngrok tunnel.

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
