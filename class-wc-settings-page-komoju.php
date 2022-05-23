<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 *KOMOJU settings.
 *
 * @class       WC_Settings_Page_Komoju
 * @extends     WC_Settings_Page
 *
 * @version     2.1.1
 *
 * @author      Komoju
 */
require_once dirname(__FILE__) . '/komoju-php/komoju-php/lib/komoju.php';

class WC_Settings_Page_Komoju extends WC_Settings_Page
{
    public function __construct()
    {
        $this->id    = 'komoju_settings';
        $this->label = __('Komoju', 'komoju-woocommerce');

        add_action(
            'woocommerce_admin_field_komoju_payment_methods',
            [$this, 'output_payment_methods']
        );

        parent::__construct();
    }

    public function get_sections()
    {
        $sections = [
            '' => __('KOMOJU account settings', 'komoju-woocommerce'),
        ];

        return apply_filters('woocommerce_get_sections_' . $this->id, $sections);
    }

    public function get_settings($current_section = '')
    {
        $settings = [];

        if ('' === $current_section) {
            $settings = apply_filters(
                'woocommerce_komoju_settings',
                include 'includes/global-settings-komoju.php'
            );
        }

        return apply_filters('woocommerce_get_settings_' . $this->id, $settings, $current_section);
    }

    public function output_payment_methods($setting)
    {
        $api = new KomojuApi($this->secret_key());
        $methods_by_slug = [];
        $locale = $this->get_locale_or_fallback();
        $all_payment_methods = [];

        // Fetch payment methods from KOMOJU
        if ($api->secretKey && strlen($api->secretKey) > 0) {
            try {
                $all_payment_methods = $api->paymentMethods();
            } catch (KomojuExceptionBadServer $ex) {
                ?>
                    <div style="color: darkred">
                        <?php echo __('Unable to reach KOMOJU. Is your secret key correct?', 'komoju-woocommerce') ?>
                    </div>
                <?php
                return;
            }
        } else {
            // TODO: hmm maybe nothing is fine actually
            return;
        }

        // Show each payment method as a checkbox with an icon
        ?>
        <h4><?php echo $setting['title']; ?></h4>
        <div style="display: flex; flex-flow: row wrap">
        <?php

        foreach ($all_payment_methods as $payment_method) {
            $slug = $payment_method['type_slug'];
            if (isset($methods_by_slug[$slug])) { continue; }
            $methods_by_slug[$slug] = $payment_method;

            ?>
            <label style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px; width: 200px">
            <input type="checkbox">
            <img
              width="38"
              height="24"
              src="https://komoju.com/payment_methods/<?php echo esc_attr($slug) ?>.svg">
            <?php echo $payment_method['name_' . $locale]; ?>
            </label>
            <?php
        }
        echo '</div>';
    }

    private function url_for_webhooks()
    {
        // In dev the relative plugin URL will remove the host name, but it
        // will appear in production instances
        return WC()->api_request_url('WC_Gateway_Komoju');
    }

    private function secret_key()
    {
        $global_option = get_option('komoju_woocommerce_secret_key');
        if (!$global_option) {
            // This is for backwards compatibility. We used to have all settings saved under
            // a single payment gateway called "Komoju". We've sinced moved to having this
            // global settings page, but want to continue supporting old setups.
            return get_option('woocommerce_komoju_settings')['secretKey'];
        }
        return $global_option;
    }

    // TODO this is copy/pasta; put it somewhere else
    private function get_locale_or_fallback()
    {
        $fallback_locale   = 'en';
        $supported_locales = ['ja', 'en', 'ko'];
        $page_locale       = get_locale();

        if (in_array($page_locale, $supported_locales)) {
            return $page_locale;
        } else {
            return $fallback_locale;
        }
    }
}
