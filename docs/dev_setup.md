# Dev Setup

This document provides a detailed setup guide for the development environment, including instructions for WordPress and WooCommerce. This was written on WordPress version 6.1.1 and WooCommerce 6.3.1.

To begin, start the docker containers:

```
$ docker-compose up
```

This will download the docker images and WordPress plugins. Once the initial setup is done (docker is no longer constantly writing text to screen), you can navigate to `127.0.0.1:8000` to setup WordPress.

**Note:** It _has_ to be `127.0.0.1`, not `localhost`

## Configuring Ngrok

Because the Komoju plugin uses webhooks to receive notifications once the payment is complete the wordpress instance will need to be accessible to the internet. This can be done with [ngrok](https://ngrok.com/):

```
ngrok http --host-header=rewrite http://127.0.0.1:8000
```

**Note:** Accessing the website using the ngrok endpoint doesn't work particularly well, so you're better off using 127.0.0.1 to go through the checkout flow and just use ngrok for the webhook integration.

## Setting up WordPress

Once on the WordPress setup screen you can fill in the details as you like (as long as you remember the username and password) then click "Install WordPress" on the bottom left.

Log in when prompted, and you should be directed to the admin page.

## Setting up WooCommerce

1. On the left sidebar, click Plugins.
2. Under the "Relative URL" name, click "Activate" to turn the relative URL plugin on (this is used to get ngrok integration working properly)
3. Under "WooCommerce" click "Activate". This will take you to a new page with the WooCommerce setup:
![Activating Plugins](./images/Activate_plugins.png)
4. Click "Yes please" to start the setup.
5. On the "Start setting up your WooCommerce store" page, scroll to the very bottom and click the "Proceed without Jetpack & WooCommerce Services" link (we don't need these for our dev environment):
![Proceed without Jetpack](./images/Proceed_without_Jetpack.png)
6. When prompted with the "Build a Better WooCommerce" pop up just click continue _without_ ticking the box.
7. Set up the address with fake data:
![Address setup](./images/Address_setup.png)
8. On the "In which industry does the store operate?" page, just select whatever you like and click "Continue"
9. On the "What type of products will be listed?" page, select Virtual or Physical:
and click "Continue"
![Product types](./images/Product_types.png)
10. On "Tell us about your business", select an amount of products, then set "Currently selling elsewhere?" to No, and disable all the options that appear below it, then click "Continue":
![About the Business](./images/About_the_business.png)
11. On the "Choose a theme" page, just select "Continue with my active theme":
![Choose a Theme](./images/Choose_a_theme.png)
12. You should be redirected back to the WordPress admin page.
13. In the left Side panel, click on "WooCommerce", and then on the "Settings" submenu.
14. Scroll down until you get to "Currency Options"
15. Set the "Currency" field to "Japanese Yen (¥)"
16. Click "Save changes" at the bottom:
![Set currency](./images/Set_currency.png)

## Setting up the Komoju WooCommerce plugin

1. On the WordPress admin page, click "Plugins" on the left side panel
2. On the Plugins page click "Activate" under "WooCommerce Komoju Gateway"
3. On the side panel, click "WooCommerce", then click the "Settings" submenu
4. On the tabs in the main content click "Payments"
5. Next to "Komoju" click the "Enabled" toggle to turn on the Komoju integration, then click "Set up" to configure it:
![Enable Komoju Plugin](./images/Enable_Komoju_plugin.png)
6. Set up the API settings with the test Komoju account

Make sure that the Webhook is configured as per the README.

## Adding a product to WooCommerce

To be able to test the checkout you will first need to have a purchasable product in your store.

1. On the WordPress admin page, click on "Products" in the left side panel, then click the "Create product" button in the main content.
2. Add a product name of your choosing
3. Add a description
4. Set the price of the object
5. Click "Publish":
![Add a Product](./images/Add_a_product.png)


If you go to http://127.0.0.1:8000/?post_type=product you should be able to see the shop, with an item for purchase you can use to test the Komoju integration.

