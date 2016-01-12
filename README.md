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
Always start the configuration by enabling the sandbox mode and do a few tests before going live.

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
