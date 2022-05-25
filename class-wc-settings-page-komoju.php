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
            'woocommerce_admin_field_komoju_payment_types',
            [$this, 'output_payment_methods']
        );

        parent::__construct();
    }

    // Override from WC_Settings_Page
    public function get_sections()
    {
        $sections = [
            '' => __('KOMOJU account settings', 'komoju-woocommerce'),
        ];

        return apply_filters('woocommerce_get_sections_' . $this->id, $sections);
    }

    // Override from WC_Settings_Page
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

    // Override from WC_Settings_Page
    public function save()
    {
        $old_payment_types = get_option('komoju_woocommerce_payment_types');
        parent::save();
        $new_payment_types = get_option('komoju_woocommerce_payment_types');

        if ($old_payment_types != $new_payment_types) {
            $this->cache_payment_methods_from_komoju($old_payment_types, $new_payment_types);
        }
    }

    // Action handler for rendering settings with type = 'komoju_payment_types'
    public function output_payment_methods($setting)
    {
        $locale              = WC_Gateway_Komoju::get_locale_or_fallback();
        $all_payment_methods = $this->fetch_all_payment_methods();
        if ($all_payment_methods === null) {
            ?>
                <div style="color: darkred">
                    <?php echo __('Unable to reach KOMOJU. Is your secret key correct?', 'komoju-woocommerce'); ?>
                </div>
            <?php
            return;
        }

        // Show each payment method as a checkbox with an icon?>
        <h4><?php echo $setting['title']; ?></h4>
        <div style="display: flex; flex-flow: row wrap; max-width: 800px">
        <?php

        foreach ($all_payment_methods as $slug => $payment_method) {
            ?>
            <label style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px; width: 200px">
            <input
              type="checkbox"
              name="<?php echo esc_attr($setting['id']); ?>[]"
              value="<?php echo esc_attr($slug); ?>"
              <?php if (in_array($slug, $setting['value'])) {
                echo 'checked';
            } ?>
            >
            <img
              width="38"
              height="24"
              src="https://komoju.com/payment_methods/<?php echo esc_attr($slug); ?>.svg">
            <?php echo $payment_method['name_' . $locale]; ?>
            </label>
            <?php
        }
        echo '</div>';
    }

    // Basically, the 'komoju_woocommerce_payment_types' option is just an array of slugs,
    // and 'komoju_woocommerce_payment_methods' holds the actual payment method objects
    // we get from the KOMOJU API.
    //
    // This action handler updates the 'komoju_woocommerce_payment_types' option
    // to match the 'komoju_woocommerce_payment_types' option.
    public function cache_payment_methods_from_komoju($old_payment_types, $payment_types)
    {
        $all_payment_methods = $this->fetch_all_payment_methods();
        if ($all_payment_methods === null) {
            return;
        }

        // Clear gateway settings from removed entries
        if ($old_payment_types) {
            $to_remove = array_diff($old_payment_types, $payment_types);
            foreach ($to_remove as $slug) {
                delete_option('woocommerce_komoju_' . $slug . '_settings');
            }
        }

        // Populate komoju_woocommerce_payment_methods option with fresh values from KOMOJU
        $payment_methods = [];
        foreach ($payment_types as $slug) {
            $payment_methods[$slug] = $all_payment_methods[$slug];
        }

        update_option('komoju_woocommerce_payment_methods', $payment_methods, true);
    }
    // TODO: override save

    private function url_for_webhooks()
    {
        // In dev the relative plugin URL will remove the host name, but it
        // will appear in production instances
        return WC()->api_request_url('WC_Gateway_Komoju');
    }

    // Returns a list of payment methods from KOMOJU.
    //
    // @return array that looks something like this:
    // [
    //     "konbini" => [
    //         "currency"           => "JPY",
    //         "name_en"            => "Konbini",
    //         "name_ja"            => "コンビニ",
    //         "name_ko"            => "편의점",
    //         "payment_method_fee" => 190,
    //         "type_slug"          => "konbini"
    //     ],
    //     ...
    // ]
    private function fetch_all_payment_methods()
    {
        $api = new KomojuApi($this->secret_key());

        if (!$api->secretKey || strlen($api->secretKey) === 0) {
            return null;
        }

        try {
            $all_payment_methods = $api->paymentMethods();
            $methods_by_slug     = [];

            foreach ($all_payment_methods as $payment_method) {
                $slug = $payment_method['type_slug'];
                if (isset($methods_by_slug[$slug])) {
                    continue;
                }
                $methods_by_slug[$slug] = $payment_method;
            }

            return $methods_by_slug;
        } catch (KomojuExceptionBadServer $ex) {
            return null;
        }
    }

    private function secret_key()
    {
        $global_option = get_option('komoju_woocommerce_secret_key');
        if (!$global_option) {
            // This is for backwards compatibility. We used to have all settings saved under
            // a single payment gateway called "Komoju". We've sinced moved to having this
            // global settings page, but want to continue supporting old setups.
            return WC_Gateway_Komoju::get_legacy_setting('secretKey');
        }

        return $global_option;
    }
}
