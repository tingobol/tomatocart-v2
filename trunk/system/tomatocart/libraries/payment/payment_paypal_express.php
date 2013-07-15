<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

require_once 'payment_module.php';

/**
 * Paypal Standard -- Payment Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Payment_paypal_express extends TOC_Payment_Module
{
    /**
     * payment module code
     *
     * @access protected
     * @var string
     */
    protected $code = 'paypal_express';

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
        array('name' => 'MODULE_PAYMENT_PAYPAL_EXPRESS_STATUS',
              'title' => 'Enable PayPal Express Module', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'True',
              'description' => 'Do you want to accept PayPal EXPRESS payments?',
              'values' => array(
                                array('id' => 'True', 'text' => 'True'),
                                array('id' => 'False', 'text' => 'False'))),
        array('name' => 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_USERNAME',
              'title' => 'API Username', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The username to use for the PayPal Web Services API.'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_PASSWORD',
              'title' => 'API Password', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The password to use for the PayPal Web Services API.'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_EXPRESS_API_SIGNATURE',
              'title' => 'API Signature', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The location of the PayPal Direct Signature for the PayPal Web Services API.'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_EXPRESS_SERVER',
              'title' => 'Transaction Server', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'Sandbox',
              'description' => 'The server to perform transactions in.',
              'values' => array(
                    array('id' => 'Production', 'text' => 'Production'),
                    array('id' => 'Sandbox', 'text' => 'Sandbox'))),
        array('name' => 'MODULE_PAYMENT_PAYPAL_EXPRESS_METHOD',
              'title' => 'Transaction Method', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'Sandbox',
              'description' => 'The method to perform transactions in.',
              'values' => array(
                    array('id' => 'Athorization', 'text' => 'Athorization'),
                    array('id' => 'Sale', 'text' => 'Sale'))),
        array('name' => 'MODULE_PAYMENT_PAYPAL_EXPRESS_SORT_ORDER',
              'title' => 'Sort order of display.', 
              'type' => 'numberfield',
              'value' => '0',
              'description' => 'Sort order of display. Lowest is displayed first.'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_EXPRESS_ZONE',
              'title' => 'Payment Zone', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'If a zone is selected, only enable this payment method for that zone.',
              'action' => 'config/get_shipping_zone'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_EXPRESS_ORDER_STATUS_ID',
              'title' => 'Set Order Status', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '',
              'description' => 'Set the status of orders made with this payment module to this value',
              'action' => 'config/get_order_status'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_EXPRESS_CURL_PROGRAM_LOCATION',
              'title' => 'cURL Program Location', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The location of the cURL Program Location.'));
        
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        parent::__construct();

        $this->title = lang('payment_paypal_express_title');
        $this->method_title = lang('payment_paypal_express_method_title');
        $this->status = (isset($this->config['MODULE_PAYMENT_PAYPAL_EXPRESS_STATUS']) && ($this->config['MODULE_PAYMENT_PAYPAL_EXPRESS_STATUS'] == 'True')) ? TRUE : FALSE;
        $this->sort_order = isset($this->config['MODULE_PAYMENT_PAYPAL_EXPRESS_SORT_ORDER']) ? $this->config['MODULE_PAYMENT_PAYPAL_EXPRESS_SORT_ORDER'] : null;
    }

    /**
     * Initialize the shipping module
     *
     * @access public
     */
    function initialize()
    {
        $this->ci->load->model('address_model');

        if ($this->config['MODULE_PAYMENT_PAYPAL_EXPRESS_SERVER'] == 'Production')
        {
            $this->api_url = 'https://api-3t.paypal.com/nvp';
            $this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout';
        }
        else
        {
            $this->api_url = 'https://api-3t.sandbox.paypal.com/nvp';
            $this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout';
        }

        if ($this->status === true)
        {
            $this->order_status = $this->config['MODULE_PAYMENT_PAYPAL_EXPRESS_ORDER_STATUS_ID'] > 0 ? (int) $this->config['MODULE_PAYMENT_PAYPAL_EXPRESS_ORDER_STATUS_ID'] : (int)config('ORDERS_STATUS_PAID');

            if ((int)$this->config['MODULE_PAYMENT_PAYPAL_EXPRESS_ZONE'] > 0)
            {
                $this->ci->load->model('address_model');
                
                $zones = $this->ci->address_model->get_zone_id_via_geo_zone($this->ci->shopping_cart->get_billing_address('country_id'), $this->config['MODULE_PAYMENT_PAYPAL_EXPRESS_ZONE']);

                $check_flag = FALSE;
                if ($zones !== NULL)
                {
                    foreach($zones as $zone_id)
                    {
                        if ($zone_id < 1)
                        {
                            $check_flag = TRUE;
                            break;
                        }
                        elseif ($zone_id == $this->ci->shopping_cart->get_billing_address('zone_id'))
                        {
                            $check_flag = TRUE;
                            break;
                        }
                    }
                }

                if ($check_flag == FALSE) {
                    $this->status = FALSE;
                }
            }
        }
    }


    function checkout_initialization_method() {
        global $osC_Language;

        if (file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'modules/payment/paypal/btn_express_' . basename($osC_Language->getCode()) . '.gif')) {
            $image = DIR_WS_IMAGES . 'modules/payment/paypal/btn_express_' . basename($osC_Language->getCode()) . '.gif';
        } else {
            $image = DIR_WS_IMAGES . 'modules/payment/paypal/btn_express.gif';
        }

        $string = osc_link_object(osc_href_link(FILENAME_CHECKOUT, 'callback&module=paypal_express'), osc_image($image, $osC_Language->get('payment_paypal_express_button_title')));

        return $string;
    }

    function selection() {
        //if (isset($_SESSION['ppe_token'])) {
            return array('id' => $this->code,
                 'module' => $this->method_title);
        //}else {
          //  return false;
        //}
    }

    function pre_confirmation_check() {
        //return osc_href_link(FILENAME_CHECKOUT,'callback&module=paypal_express');
    }

    function confirmation() {
        return false;
    }

    function process_button() {
        return false;
    }


    function process() {
        global $osC_ShoppingCart, $osC_Currencies, $messageStack;

        $orders_id = osC_Order::insert();

        $params = array('USER' => MODULE_PAYMENT_PAYPAL_EXPRESS_API_USERNAME,
                      'PWD' => MODULE_PAYMENT_PAYPAL_EXPRESS_API_PASSWORD,
                      'VERSION' => '3.2',
                      'SIGNATURE' => MODULE_PAYMENT_PAYPAL_EXPRESS_API_SIGNATURE,
                      'METHOD' => 'DoExpressCheckoutPayment',
                      'TOKEN' => $_SESSION['ppe_token'],
                      'PAYMENTACTION' => ((MODULE_PAYMENT_PAYPAL_EXPRESS_METHOD == 'Sale') ? 'Sale' : 'Authorization'),
                      'PAYERID' => $_SESSION['ppe_payerid'],
                      'AMT' => $osC_Currencies->formatRaw($osC_ShoppingCart->getTotal() - $osC_ShoppingCart->getShippingMethod('cost'), $osC_Currencies->getCode()),
                      'CURRENCYCODE' => $osC_Currencies->getCode(),
                      'BUTTONSOURCE' => PROJECT_VERSION);

        if ($osC_ShoppingCart->hasShippingAddress()) {
            $params['SHIPTONAME'] = $osC_ShoppingCart->getShippingAddress('firstname') . ' ' . $osC_ShoppingCart->getShippingAddress('lastname');
            $params['SHIPTOSTREET'] = $osC_ShoppingCart->getShippingAddress('street_address');
            $params['SHIPTOCITY'] = $osC_ShoppingCart->getShippingAddress('city');
            $params['SHIPTOSTATE'] = $osC_ShoppingCart->getShippingAddress('zone_code');
            $params['SHIPTOCOUNTRYCODE'] = $osC_ShoppingCart->getShippingAddress('country_iso_code_2');
            $params['SHIPTOZIP'] = $osC_ShoppingCart->getShippingAddress('postcode');
        }

        $post_string = '';
        foreach ($params as $key => $value) {
            $post_string .= $key . '=' . urlencode(trim($value)) . '&';
        }
        $post_string = substr($post_string, 0, -1);
         
        $response = $this->sendTransactionToGateway($this->api_url, $post_string);
        $response_array = array();
        parse_str($response, $response_array);

        unset($_SESSION['ppe_token']);
        unset($_SESSION['ppe_payerid']);

        if (($response_array['ACK'] != 'Success') && ($response_array['ACK'] != 'SuccessWithWarning')) {
            $messageStack->add_session('shopping_cart', stripslashes($response_array['L_LONGMESSAGE0']), 'error');

            osc_redirect(osc_href_link(FILENAME_CHECKOUT, '', 'SSL'));
            exit;
        }else {
            osC_Order::process($orders_id, $this->order_status);
        }
    }

    function callback() {
        global $osC_Database, $osC_ShoppingCart, $osC_Currencies;

        if (!$osC_ShoppingCart->hasContents()) {
            osc_redirect(osc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
        }

        $params = array('USER' => MODULE_PAYMENT_PAYPAL_EXPRESS_API_USERNAME,
                      'PWD' => MODULE_PAYMENT_PAYPAL_EXPRESS_API_PASSWORD,
                      'VERSION' => '3.2',
                      'SIGNATURE' => MODULE_PAYMENT_PAYPAL_EXPRESS_API_SIGNATURE);

        if (isset($_GET['express_action']) && ($_GET['express_action'] == 'retrieve')) {
            self::_get_express_checkout_details($params);
        } else {
            self::_set_express_checkout($params);
        }

        exit;
    }

    function _get_express_checkout_details($params) {
        global $osC_ShoppingCart, $osC_Database, $osC_Customer;

        $params['METHOD'] = 'GetExpressCheckoutDetails';
        $params['TOKEN'] = $_GET['token'];

        $post_string = '';
        foreach ($params as $key => $value) {
            $post_string .= $key . '=' . urlencode(trim($value)) . '&';
        }
        $post_string = substr($post_string, 0, -1);

        $response = $this->sendTransactionToGateway($this->api_url, $post_string);
         
        $response_array = array();
        parse_str($response, $response_array);

        if (($response_array['ACK'] == 'Success') || ($response_array['ACK'] == 'SuccessWithWarning')) {
            if ($osC_ShoppingCart->getContentType() != 'virtual') {
                $country_query = $osC_Database->query('select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format from :table_countries where countries_iso_code_2 = :country_iso_code_2');
                $country_query->bindTable(':table_countries', TABLE_COUNTRIES);
                $country_query->bindValue(':country_iso_code_2', $response_array['SHIPTOCOUNTRYCODE']);
                $country_query->execute();

                $country = $country_query->toArray();

                $zone_name = $response_array['SHIPTOSTATE'];
                $zone_id = 0;

                $zone_query = $osC_Database->query('select zone_id, zone_name from :table_zones where zone_country_id = :zone_country_id and zone_code = :zone_code');
                $zone_query->bindTable(':table_zones', TABLE_ZONES);
                $zone_query->bindInt(':zone_country_id', $country['countries_id']);
                $zone_query->bindValue(':zone_code', $response_array['SHIPTOSTATE']);
                $zone_query->execute();

                if ($zone_query->numberOfRows()) {
                    $zone = $zone_query->toArray();
                    $zone_name = $zone['zone_name'];
                    $zone_id = $zone['zone_id'];
                }

                $sendto = array('firstname' => substr($response_array['SHIPTONAME'], 0, strpos($response_array['SHIPTONAME'], ' ')),
                          'lastname' => substr($response_array['SHIPTONAME'], strpos($response_array['SHIPTONAME'], ' ')+1),
                          'company' => '',
                          'street_address' => $response_array['SHIPTOSTREET'],
                          'suburb' => '',
                          'email_address' => $response_array['EMAIL'],
                          'postcode' => $response_array['SHIPTOZIP'],
                          'city' => $response_array['SHIPTOCITY'],
                          'zone_id' => $zone_id,
                          'zone_name' => $zone_name,
                          'country_id' => $country['countries_id'],
                          'country_name' => $country['countries_name'],
                          'country_iso_code_2' => $country['countries_iso_code_2'],
                          'country_iso_code_3' => $country['countries_iso_code_3'],
                          'address_format_id' => ($country['address_format_id'] > 0 ? $country['address_format_id'] : '1'));

                $osC_ShoppingCart->setRawShippingAddress($sendto);
                $osC_ShoppingCart->setRawBillingAddress($sendto);
                $osC_ShoppingCart->setBillingMethod(array('id' => $this->getCode(), 'title' => $this->getMethodTitle()));

                if (!isset($_SESSION['payment'])) {
                    $_SESSION['payment'] = $this->getCode();
                }

                if (!isset($_SESSION['ppe_token'])) {
                    $_SESSION['ppe_token'] = $response_array['TOKEN'];
                }

                if (!isset($_SESSION['ppe_payerid'])) {
                    $_SESSION['ppe_payerid'] = $response_array['PAYERID'];
                }

                if ($osC_Customer->isLoggedOn() === true) {
                    osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&express=active&view=shippingMethodForm', 'SSL'));
                } else if ($this->_findEmail($response_array['EMAIL'])) {
                    osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout', 'SSL'));
                } else {
                    osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&express=active&view=billingInformationForm', 'SSL'));
                }
            }
        }
    }

    function _set_express_checkout($params) {
        global $osC_ShoppingCart, $osC_Currencies;

        $params['METHOD'] = 'SetExpressCheckout';
        $params['PAYMENTACTION'] = ((MODULE_PAYMENT_PAYPAL_EXPRESS_METHOD == 'Sale') ? 'Sale' : 'Authorization');
        $params['RETURNURL'] = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG . FILENAME_CHECKOUT . '?callback&module=paypal_express&express_action=retrieve';
        //$params['RETURNURL'] = osc_href_link(FILENAME_CHECKOUT, 'callback&module=paypal_express&express_action=retrieve', 'NONSSL', true, true, true);
        $params['CANCELURL'] = osc_href_link(FILENAME_CHECKOUT, '', 'NONSSL', true, true, true);
        $params['AMT'] =  $osC_Currencies->formatRaw($osC_ShoppingCart->getTotal() - $osC_ShoppingCart->getShippingMethod('cost'), $osC_Currencies->getCode());
        $params['CURRENCYCODE'] = $osC_Currencies->getCode();

        if ($osC_ShoppingCart->getContentType() == 'virtual') {
            $params['NOSHIPPING'] = '1';
        }

        if ($osC_ShoppingCart->hasShippingAddress()) {
            $params['SHIPTOSTREET'] = $osC_ShoppingCart->getShippingAddress('street_address');
            $params['SHIPTOCITY'] = $osC_ShoppingCart->getShippingAddress('city');
            $params['SHIPTOSTATE'] = $osC_ShoppingCart->getShippingAddress('state');
            $params['SHIPTOCOUTRYCODE'] = $osC_ShoppingCart->getShippingAddress('country_iso_code_2');
            $params['SHIPTOZIP'] = $osC_ShoppingCart->getShippingAddress('postcode');
        }

        $post_string = '';
        foreach ($params as $key => $value) {
            $post_string .= $key . '=' . urlencode(trim($value)) . '&';
        }
        $post_string = substr($post_string, 0, -1);

        $response = $this->sendTransactionToGateway($this->api_url, $post_string);

        $response_array = array();
        parse_str($response, $response_array);

        if (($response_array['ACK'] == 'Success') || ($response_array['ACK'] == 'SuccessWithWarning')) {
            osc_redirect($this->paypal_url . '&token=' . $response_array['TOKEN']);
        } else {
            osc_redirect(osc_href_link(FILENAME_CHECKOUT, '', 'SSL'));
        }
    }

    function _findEmail($email) {
        global $osC_Database;

        $Qcheck = $osC_Database->query('select customers_id from :table_customers where customers_email_address = :email');
        $Qcheck->bindTable(':table_customers', TABLE_CUSTOMERS);
        $Qcheck->bindValue(':email', $email);
        $Qcheck->execute();

        if ($Qcheck->numberOfRows() > 0) {
            return true;
        }else {
            return false;
        }
    }
}
?>