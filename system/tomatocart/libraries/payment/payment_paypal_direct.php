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
class TOC_Payment_paypal_direct extends TOC_Payment_Module
{
    /**
     * payment module code
     *
     * @access protected
     * @var string
     */
    protected $code = 'paypal_direct';

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_STATUS',
              'title' => 'Enable PayPal Direct Module', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'True',
              'description' => 'Do you want to accept PayPal Direct payments?',
              'values' => array(
                                array('id' => 'True', 'text' => 'True'),
                                array('id' => 'False', 'text' => 'False'))),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_API_USERNAME',
              'title' => 'API Username', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The username to use for the PayPal Web Services API.'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_API_PASSWORD',
              'title' => 'API Password', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The password to use for the PayPal Web Services API.'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_API_SIGNATURE',
              'title' => 'API Signature', 
              'type' => 'textfield',
		   	  'value' => '',
              'description' => 'The location of the PayPal Direct Signature for the PayPal Web Services API.'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_SERVER',
              'title' => 'Transaction Server', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'Sandbox',
              'description' => 'The server to perform transactions in.',
              'values' => array(
                    array('id' => 'Production', 'text' => 'Production'),
                    array('id' => 'Sandbox', 'text' => 'Sandbox'))),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_METHOD',
              'title' => 'Transaction Method', 
              'type' => 'combobox',
              'mode' => 'local',
              'value' => 'Sandbox',
              'description' => 'The method to perform transactions in.',
              'values' => array(
                    array('id' => 'Athorization', 'text' => 'Athorization'),
                    array('id' => 'Sale', 'text' => 'Sale'))),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_SORT_ORDER',
              'title' => 'Sort order of display.', 
              'type' => 'numberfield',
              'value' => '0',
              'description' => 'Sort order of display. Lowest is displayed first.'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_ZONE',
              'title' => 'Payment Zone', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '0',
              'description' => 'If a zone is selected, only enable this payment method for that zone.',
              'action' => 'config/get_shipping_zone'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_ORDER_STATUS_ID',
              'title' => 'Set Order Status', 
              'type' => 'combobox',
              'mode' => 'remote',
		   	  'value' => '',
              'description' => 'Set the status of orders made with this payment module to this value',
              'action' => 'config/get_order_status'),
        array('name' => 'MODULE_PAYMENT_PAYPAL_DIRECT_CURL_PROGRAM_LOCATION',
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

        $this->title = lang('payment_paypal_direct_title');
        $this->method_title = lang('payment_paypal_direct_method_title');
        $this->status = (isset($this->config['MODULE_PAYMENT_PAYPAL_DIRECT_STATUS']) && ($this->config['MODULE_PAYMENT_PAYPAL_DIRECT_STATUS'] == 'True')) ? TRUE : FALSE;
        $this->sort_order = isset($this->config['MODULE_PAYMENT_PAYPAL_DIRECT_SORT_ORDER']) ? $this->config['MODULE_PAYMENT_PAYPAL_DIRECT_SORT_ORDER'] : null;

        $this->cc_types = array('VISA'     => 'Visa',
                              'MASTERCARD' => 'MasterCard',
                              'DISCOVER'   => 'Discover Card',
                              'AMEX'       => 'American Express',
                              'SWITCH'     => 'Maestro',
                              'SOLO'       => 'Solo');
    }

    /**
     * Initialize the shipping module
     *
     * @access public
     */
    function initialize()
    {
        $this->ci->load->model('address_model');

        if ($this->config['MODULE_PAYMENT_PAYPAL_DIRECT_SERVER'] == 'Production')
        {
            $this->api_url = 'https://api-3t.paypal.com/nvp';
        }
        else
        {
            $this->api_url = 'https://api-3t.sandbox.paypal.com/nvp';
        }

        if ($this->status === TRUE)
        {
            $this->order_status = $this->config['MODULE_PAYMENT_PAYPAL_DIRECT_ORDER_STATUS_ID'] > 0 ? (int) $this->config['MODULE_PAYMENT_PAYPAL_DIRECT_ORDER_STATUS_ID'] : (int)config('ORDERS_STATUS_PAID');

            if ((int)$this->config['MODULE_PAYMENT_PAYPAL_DIRECT_ZONE'] > 0)
            {
                $zones = $this->ci->address_model->get_zone_id_via_geo_zone($this->ci->shopping_cart->get_billing_address('country_id'), $this->config['MODULE_PAYMENT_PAYPAL_DIRECT_ZONE']);

                $check_flag = FALSE;
                if ($zones !== FALSE)
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

    /**
     * Get selected payment module
     *
     * @access public
     * @return payment module selection
     */
    function selection()
    {
        return array('id' => $this->code, 'module' => $this->method_title);
    }

    /**
     * Get selected payment module
     *
     * @access public
     * @return payment module selection
     */
    function confirmation() {
        global $osC_ShoppingCart, $osC_Language;

        $types_array = array();
        foreach($this->cc_types as $key => $value) {
            $types_array[] = array('id' => $key,
                               'text' => $value);
        }

        $today = getdate();

        $months_array = array();
        for ($i=1; $i<13; $i++) {
            $months_array[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
        }

        $year_valid_from_array = array();
        for ($i=$today['year']-10; $i < $today['year']+1; $i++) {
            $year_valid_from_array[] = array('id' => strftime('%Y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $year_expires_array = array();
        for ($i=$today['year']; $i < $today['year']+10; $i++) {
            $year_expires_array[] = array('id' => strftime('%Y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $confirmation = array('fields' => array(array('title' => $osC_Language->get('payment_paypal_direct_card_owner'),
                                                    'field' => osc_draw_input_field('cc_owner', $osC_ShoppingCart->getBillingAddress('firstname') . ' ' . $osC_ShoppingCart->getBillingAddress('lastname'))),
        array('title' => $osC_Language->get('payment_paypal_direct_card_type'),
                                                    'field' => osc_draw_pull_down_menu('cc_type', $types_array)),
        array('title' => $osC_Language->get('payment_paypal_direct_card_number'),
                                                    'field' => osc_draw_input_field('cc_number_nh-dns')),
        array('title' => $osC_Language->get('payment_paypal_direct_card_valid_from'),
                                                    'field' => osc_draw_pull_down_menu('cc_starts_month', $months_array) . '&nbsp;' . osc_draw_pull_down_menu('cc_starts_year', $year_valid_from_array) . ' ' . $osC_Language->get('payment_paypal_direct_card_valid_from_info')),
        array('title' => $osC_Language->get('payment_paypal_direct_card_expires'),
                                                    'field' => osc_draw_pull_down_menu('cc_expires_month', $months_array) . '&nbsp;' . osc_draw_pull_down_menu('cc_expires_year', $year_expires_array)),
        array('title' => $osC_Language->get('payment_paypal_direct_card_cvc'),
                                                    'field' => osc_draw_input_field('cc_cvc_nh-dns', '', 'size="5" maxlength="4"')),
        array('title' => $osC_Language->get('payment_paypal_direct_card_issue_number'),
                                                    'field' => osc_draw_input_field('cc_issue_nh-dns', '', 'size="3" maxlength="2"') . ' ' . $osC_Language->get('payment_paypal_direct_card_issue_number_info'))));

        return $confirmation;
    }

    function process_button() {
        return false;
    }

    function process() {
        global $osC_Currencies, $osC_ShoppingCart, $osC_Customer, $osC_Language, $messageStack;

        $currency = $osC_Currencies->getCode();

        if (isset($_POST['cc_owner']) && !empty($_POST['cc_owner']) && isset($_POST['cc_type']) && isset($this->cc_types[$_POST['cc_type']]) && isset($_POST['cc_number_nh-dns']) && !empty($_POST['cc_number_nh-dns'])) {
            $params = array('USER' => MODULE_PAYMENT_PAYPAL_DIRECT_API_USERNAME,
                        'PWD' => MODULE_PAYMENT_PAYPAL_DIRECT_API_PASSWORD,
                        'VERSION' => '3.2',
                        'SIGNATURE' => MODULE_PAYMENT_PAYPAL_DIRECT_API_SIGNATURE,
                        'METHOD' => 'DoDirectPayment',
                        'PAYMENTACTION' => ((MODULE_PAYMENT_PAYPAL_DIRECT_TRANSACTION_METHOD == 'Sale') ? 'Sale' : 'Authorization'),
                        'IPADDRESS' => osc_get_ip_address(),
                        'AMT' => $osC_Currencies->formatRaw($osC_ShoppingCart->getTotal() - $osC_ShoppingCart->getShippingMethod('cost'), $currency),
                        'CREDITCARDTYPE' => $_POST['cc_type'],
                        'ACCT' => $_POST['cc_number_nh-dns'],
                        'STARTDATE' => $_POST['cc_starts_month'] . $_POST['cc_starts_year'],
                        'EXPDATE' => $_POST['cc_expires_month'] . $_POST['cc_expires_year'],
                        'CVV2' => $_POST['cc_cvc_nh-dns'],
                        'FIRSTNAME' => substr($_POST['cc_owner'], 0, strpos($_POST['cc_owner'], ' ')),
                        'LASTNAME' => substr($_POST['cc_owner'], strpos($_POST['cc_owner'], ' ') + 1),
                        'STREET' => $osC_ShoppingCart->getBillingAddress('street_address'),
                        'CITY' => $osC_ShoppingCart->getBillingAddress('city'),
                        'STATE' => $osC_ShoppingCart->getBillingAddress('state'),
                        'COUNTRYCODE' => $osC_ShoppingCart->getBillingAddress('country_iso_code_2'),
                        'ZIP' => $osC_ShoppingCart->getBillingAddress('postcode'),
                        'EMAIL' => $osC_Customer->getEmailAddress(),
                        'PHONENUM' => $osC_ShoppingCart->getBillingAddress('telephone_number'),
                        'CURRENCYCODE' => $currency,
                        'BUTTONSOURCE' => 'tomatcart');

            if ( ($_POST['cc_type'] == 'SWITCH') || ($_POST['cc_type'] == 'SOLO') ) {
                $params['ISSUENUMBER'] = $_POST['cc_issue_nh-dns'];
            }

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

            if (($response_array['ACK'] != 'Success') && ($response_array['ACK'] != 'SuccessWithWarning')) {
                $messageStack->add_session('checkout', stripslashes($response_array['L_LONGMESSAGE0']), 'error');

                osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&view=orderConfirmationForm', 'SSL'));
            }else {
                $orders_id = osC_Order::insert();

                $comments = 'PayPal Website Payments Pro (US) Direct Payments [' . 'ACK: ' . $response_array['ACK'] . '; TransactionID: ' . $response_array['TRANSACTIONID'] . ';' . ']';
                osC_Order::process($orders_id, ORDERS_STATUS_PAID, $comments);
            }
        }else {
            $messageStack->add_session('checkout', $osC_Language->get('payment_paypal_direct_error_all_fields_required'), 'error');

            osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&view=orderConfirmationForm', 'SSL'));
        }
    }

    function callback() {
        return false;
    }
}