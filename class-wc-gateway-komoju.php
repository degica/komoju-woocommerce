<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 *Komoju Payment Gateway
 *
 * Provides access to Japanese local payment methods.
 *
 * @class       WC_Gateway_Komoju
 * @extends     WC_Payment_Gateway
 *
 * @version     2.1.1
 *
 * @author      Komoju
 */
require_once dirname(__FILE__) . '/komoju-php/komoju-php/lib/komoju.php';

class WC_Gateway_Komoju extends WC_Payment_Gateway
{
    /** @var array Array of locales */
    public $locale;

    /** @var bool Whether or not logging is enabled */
    public static $log_enabled;

    /** @var WC_Logger Logger instance */
    public static $log;

    /**
     * Constructor for the gateway.
     */
    public function __construct()
    {
        $this->id                   = $this->id ? $this->id : 'komoju';
        $this->has_fields           = gettype($this->has_fields) == 'boolean' ? $this->has_fields : true;
        $this->method_title         = $this->method_title ? $this->method_title : __('Komoju', 'komoju-woocommerce');
        $this->method_description   = __('Allows payments by Komoju, dedicated to Japanese online and offline payment gateways.', 'komoju-woocommerce');
        $this->debug                = 'yes' === $this->get_option_compat('debug_log', 'debug');
        $this->invoice_prefix       = $this->get_option_compat('invoice_prefix', 'invoice_prefix');
        $this->secretKey            = $this->get_option_compat('secret_key', 'secretKey');
        $this->webhookSecretToken   = $this->get_option_compat('webhook_secret', 'webhookSecretToken');
        $this->komoju_api           = new KomojuApi($this->secretKey);
        self::$log_enabled          = $this->debug;

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables
        $this->title                = $this->get_option('title');
        $this->description          = $this->get_option('description');
        $this->instructions         = $this->get_option('instructions', $this->description);
        $this->useOnHold            = $this->get_option('useOnHold');

        // Filters
        // Actions
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        if (!$this->is_valid_for_use()) {
            $this->enabled = 'no';
            WC_Gateway_Komoju::log('is not valid for use. No IPN set.');
        } else {
            include_once 'includes/class-wc-gateway-komoju-ipn-handler.php';
            new WC_Gateway_Komoju_IPN_Handler(
              $this,
              $this->webhookSecretToken,
              $this->secretKey,
              $this->invoice_prefix,
              $this->useOnHold
            );
        }
    }

    /**
     * Logging method
     *
     * @param string $message
     */
    public static function log($message)
    {
        if (self::$log_enabled) {
            if (empty(self::$log)) {
                self::$log = new WC_Logger();
            }
            self::$log->add('komoju', $message);
        }
    }

    /**
     * Check if this gateway is enabled and available in the user's country
     *
     * @return bool
     */
    public function is_valid_for_use()
    {
        return in_array(get_woocommerce_currency(), apply_filters('woocommerce_komoju_supported_currencies', ['JPY']));
    }

    /**
     * Admin Panel Options
     */
    public function admin_options()
    {
        if ($this->is_valid_for_use()) {
            parent::admin_options();
        } else {
            ?>
            <div class="inline error"><p><strong><?php _e('Gateway Disabled', 'komoju-woocommerce'); ?></strong>: <?php _e('Komoju does not support your store currency.', 'komoju-woocommerce'); ?></p></div>
            <?php
        }
    }

    /**
     * Initialise Gateway Settings Form Fields
     */
    public function init_form_fields()
    {
        $this->form_fields = include 'includes/gateway-settings-komoju.php';
    }

    /**
     * Process the payment and return the result
     *
     * @param int $order_id
     * @param string $payment_type
     *
     * @return array
     */
    public function process_payment($order_id, $payment_type = null)
    {
        include_once 'includes/class-wc-gateway-komoju-request.php';
        $order      = wc_get_order($order_id);
        $return_url = WC()->api_request_url('WC_Gateway_Komoju');

        if ($payment_type === null) {
            $payment_type = sanitize_text_field($_POST['komoju-method']);
        }

        // construct line items
        $line_items = [];
        foreach ($order->get_items() as $item) {
            $image_parser = new DomDocument();
            $image_parser->loadHTML($item->get_product()->get_image());
            $img = $image_parser->getElementsByTagName('img')->item(0);

            $line_items[] = [
                'description' => $item->get_name(),
                'quantity'    => $item->get_quantity(),
                'image'       => $img->attributes->getNamedItem('src')->nodeValue,
            ];
        }

        $name            = null;
        $email           = null;
        $billing_address = null;
        if ($order->has_billing_address()) {
            $billing_address = [
                'zipcode'         => $order->get_billing_postcode(),
                'street_address1' => $order->get_billing_address_1(),
                'street_address2' => trim(join(' ', [$order->get_billing_address_2(), $order->get_billing_company()])),
                'country'         => $order->get_billing_country(),
                'state'           => $order->get_billing_state(),
                'city'            => $order->get_billing_city(),
            ];

            $name  = trim(join(' ', [$order->get_billing_first_name(), $order->get_billing_last_name()]));
            $email = $order->get_billing_email();
        }

        $shipping_address = null;
        if ($order->has_shipping_address()) {
            $shipping_address = [
                'zipcode'         => $order->get_shipping_postcode(),
                'street_address1' => $order->get_shipping_address_1(),
                'street_address2' => trim(join(' ', [$order->get_shipping_address_2(), $order->get_shipping_company()])),
                'country'         => $order->get_shipping_country(),
                'state'           => $order->get_shipping_state(),
                'city'            => $order->get_shipping_city(),
            ];
        }

        // new session
        $komoju_api     = $this->komoju_api;
        $komoju_request = $komoju_api->createSession([
            'return_url'     => $return_url,
            'default_locale' => self::get_locale_or_fallback(),
            'email'          => $email,
            'payment_types'  => [$payment_type],
            'payment_data'   => [
                'amount'             => $order->get_total(),
                'currency'           => get_woocommerce_currency(),
                'external_order_num' => $this->external_order_num($order),
                'billing_address'    => $billing_address,
                'name'               => $name,
                'shipping_address'   => $shipping_address,
            ],
            'line_items' => $line_items,
        ]);

        return [
            'result'   => 'success',
            'redirect' => $komoju_request->session_url,
        ];
    }

    /**
     * Payment form on checkout page
     */
    public function payment_fields()
    {
        $this->komoju_method_form();
    }

    /**
     * set KOMOJU side reference for order
     *
     * @param WC_Order $order
     */
    private function external_order_num($order)
    {
        $suffix = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);

        return $this->get_option('invoice_prefix') . $order->get_order_number() . '-' . $suffix;
    }

    /**
     * Form to choose the payment method
     */
    private function komoju_method_form($args = [], $fields = [])
    {
        $default_args = [
            'fields_have_names' => true,
        ];

        $args = wp_parse_args($args, apply_filters('woocommerce_komoju_method_form_args', $default_args, $this->id));

        $data          = $this->get_input_field_data();
        $method_fields = ['method-field' => $data];
        $fields        = wp_parse_args($fields, apply_filters('woocommerce_komoju_method_form_fields', $method_fields, $this->id)); ?>
        <fieldset id="<?php echo $this->id; ?>-cc-form">
          <?php do_action('woocommerce_komoju_method_form_start', $this->id); ?>
          <?php
            foreach ($fields as $field) {
                echo $field;
            } ?>
          <?php do_action('woocommerce_komoju_method_form_end', $this->id); ?>
          <div class="clear"></div>
        </fieldset>
        <?php
    }

    private function get_input_field_data()
    {
        $komoju_client = $this->komoju_api;

        try {
            $methods       = apply_filters('woocommerce_komoju_payment_methods', $komoju_client->paymentMethods());
            $page_locale   = $this->get_locale_or_fallback();
            $name_property = "name_{$page_locale}";

            $field_data = '
                <p
                  class="
                    form-row
                    form-row-wide
                    validate-required
                    woocommerce-validated"
                >
                ' . __('Method of payment:', 'komoju-woocommerce') . '
                  <abbr
                    class="required"
                    title="required"
                  >*
                  </abbr>';
            foreach ($methods as $method) {
                $field_data .= '
                  <label>
                    <input
                      id="' . esc_attr($this->id) . '-method"
                      class="input-radio"
                      type="radio"
                      value="' . esc_attr($method['type_slug']) . '"
                      name="' . esc_attr($this->id) . '-method"
                    />
                    ' . ($method[$name_property]) . '
                    <br/>
                  </label>';
            }
            $field_data .= '</p>';
        } catch (KomojuExceptionBadServer | KomojuExceptionBadJson $e) {
            $message = $e->getMessage();
            $this->log($message);

            $field_data = '<p>' . __('Encountered an issue communicating with KOMOJU. Please wait a moment and try again.', 'komoju-woocommerce') . '</p>';
        }

        return $field_data;
    }

    public static function get_locale_or_fallback()
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

    /**
     * Validate the payment form (for custom fields added)
     */
    public function validate_fields()
    {
        if (!isset($_POST['komoju-method'])) {
            wc_add_notice(__('Please select a payment method (how you want to pay)', 'komoju-woocommerce'), 'error');

            return false;
        }

        return true;
    }

    /**
     * We moved a lot of gateway settings into global options.
     *
     * We don't want to require people to go update their settings, so we use this function to
     * first check for the new setting, and then use the old one if not present.
     */
    public function get_option_compat($new_global_key, $old_local_key)
    {
        $new_option = get_option('komoju_woocommerce_' . $new_global_key);
        if ($new_option) {
            return $new_option;
        }

        return self::get_legacy_setting($old_local_key);
    }

    /**
     * Quick helper for grabbing legacy settings.
     *
     * We used to have everything saved on the gateway, but now global stuff like
     * API keys are stored separately in order to support multiple gateways.
     */
    public static function get_legacy_setting($name, $default_value = null)
    {
        $legacy_settings = get_option('woocommerce_komoju_settings');
        if ($legacy_settings === false) {
            return $default_value;
        }

        if (isset($legacy_settings[$name])) {
            return $legacy_settings[$name];
        }

        return $default_value;
    }

    /**
     * Default customer-facing title of this payment gateway.
     */
    protected function default_title()
    {
        return __('Komoju', 'komoju-woocommerce');
    }
}
