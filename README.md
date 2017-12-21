**THIS PLUGIN IS CURRENTLY UNMAINTAINED AND PROVIDED AS-IS, WITHOUT ANY
GUARANTEE OF SUPPORT.**

---

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

Note - an account with [Komoju](https://komoju.com) is required before using this
module.

## Installation

Upload the contents of this repositoryto your server where wordpress and WooCommerce are installed
via FTP or other file transfer method to the wordpress/wp-content/plugins
directory

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

Enter your Komoju API credentials in this configuration page. Those data to set here are the ones defined in your Komoju dashboard.
Make sure they match.
Always start the configuration by using the test mode credentials and do a few tests before going live.

To enable Debug click the 'Enable logging' box.

Click Save Changes and the plugin is now ready to be tested.

## Frequently asked questions

### What versions of WooCommerce is this compatable with?

At the moment, this plugin has been tested and is known to work up to version
2.4.12. If you are using a later version, please contact us regarding this.

### Where can I get more information?

Please contact support@degica.com if you have any questions about
the installation of the module. If you would like to setup an account to use
with this module, please contact https://www.degica.com/contact/

## License

Komoju WooCommerce plugin copyright © 2016 by Degica Ltd.

This is a free plugin for use by Komoju customers. This code is not be traded or sold for profit.

## Development

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
