=== KOMOJU Japanese Payments ===
Contributors: degica
Tags: WooCommerce,Payment Gateway,Komoju
Requires at least: 5.3
Tested up to: 5.7.1
Stable tag: trunk
Requires PHP: 7.2
WC requires at least: 3.0.0
WC tested up to: 5.2.0
License: MIT
License URI: https://directory.fsf.org/wiki/License:X11

== Description ==

KOMOJUは革新的なオンライン決済を提供する決済プラットフォームです。KOMOJUプラグインをインストールして、ビジネスを成長させましょう。

KOMOJUを使えば、あなたのWooCommerceストア上ですべての主要な決済手段を直接利用することができます。
サポートしている決済方法：
クレジットカード（Visa, Mastercard, American Express, JCB, Diners Club）
銀行振込
コンビニ支払い
スマホ決済（PayPay、LINE Pay、メルペイ）
Pay-easy（ペイジー）
キャリア決済（au、docomo、SoftBank）
電子マネー（BitCash、WebMoney、NET CASH）

KOMOJUを選ぶ理由

KOMOJUは初期費用や月額料金が掛からず、取引ごとに手数料を支払うだけです。また、週次振込を導入することで、売上金を最短5営業日で受け取ることができます。

簡単なサインアップと迅速な承認

KOMOJUのプラグインは簡単にインストールでき、わずかなステップでKOMOJUとあなたのストアを接続することができます。KOMOJUアカウントにサインアップすると、2～3営業日で承認されます。

最高のセキュリティスタンダード

当社のWooCommerceプラグインは、セキュリティを十分に考慮して開発されています。すべてのカード会員データは当社のサーバーで処理され、PCI-DSSに完全に準拠しています。あなたの顧客は常に安全な方法で支払いを完了することができます。

不正検知

当社独自の不正防止システムは、不正な取引を事前に検知し、不要なチャージバック、その他の不正行為を未然に防ぎます。 システムは機械学習により自動的に最適化されます。

Install KOMOJU plugin and start growing your business with our innovative payment solutions.

KOMOJU allows you to accept all major payment methods directly on your WooCommerce store.
We support:
Credit cards (Visa, Mastercard, American Express, JCB, Diners Club)
Bank transfer
Convenience store payments (konbini)
Smartphone payments (PayPay, LINE Pay, Merpay)
Pay-easy
Carrier billing (au, docomo, SoftBank)
E-money (BitCash, WebMoney, NET CASH)

Why choose KOMOJU?

KOMOJU has no sign up fees and no monthly fees - you pay per transaction only. Weekly payout option allows you to have your sales transferred in as little as 5 business days.

Simple sign-up and fast approval

Our plugin is easy to install, and takes it just a few steps to get KOMOJU connected to your store. Sign up for your KOMOJU account from our website and have your application approved in 2-3 business days.

High Security Standards

Our plugin is developed with security in mind. All cardholder data is processed on our servers, and fully compliant with PCI-DSS. Your customers will always have a secure way to complete their payments.

Fraud Prevention System

Our in-house fraud prevention system can identify fraud transactions before they take place to prevent chargebacks and other fraud. Thanks to machine learning, our system adapts automatically and stops fraud in its tracks

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
