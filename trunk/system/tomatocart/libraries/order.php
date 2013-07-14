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

/**
 * Order Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class TOC_Order
{
    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    private $ci = null;

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();
    }

    /**
     * Create order
     *
     * @access public
     * @return boolean
     */
    public function create_order()
    {
        //create account
        if (!$this->ci->customer->is_logged_on())
        {
            //get billing address
            $billing_address = $this->ci->shopping_cart->get_billing_address();

            $data['customers_gender'] = $billing_address['gender'];
            $data['customers_firstname'] = $billing_address['firstname'];
            $data['customers_lastname'] = $billing_address['lastname'];
            $data['customers_newsletter'] = 0;
            $data['customers_dob'] = NULL;
            $data['customers_email_address'] = $billing_address['email_address'];
            $data['customers_password'] = encrypt_password($billing_address['password']);
            $data['customers_status'] = 1;

            //load model
            $this->ci->load->model('account_model');

            if ($this->ci->account_model->insert($data))
            {
                //set data to session
                $this->ci->customer->set_data($data['customers_email_address']);
            }
        }
        else
        {
            //get billing address
            //$billing_address = $this->ci->shopping_cart->get_billing_address();

            //if create billing address
            if (isset($billing_address['create_billing_address']) && ($billing_address['create_billing_address'] == 'on'))
            {
                $data['entry_gender'] = $billing_address['gender'];
                $data['entry_firstname'] = $billing_address['firstname'];
                $data['entry_lastname'] = $billing_address['lastname'];
                $data['entry_company'] = $billing_address['company'];
                $data['entry_street_address'] = $billing_address['street_address'];
                $data['entry_suburb'] = $billing_address['suburb'];
                $data['entry_postcode'] = $billing_address['postcode'];
                $data['entry_city'] = $billing_address['city'];
                $data['entry_country_id'] = $billing_address['country_id'];
                $data['entry_zone_id'] = $billing_address['zone_id'];
                $data['entry_telephone'] = $billing_address['telephone_number'];
                $data['entry_fax'] = $billing_address['fax'];
                $primary = $this->ci->customer->has_default_address() ? FALSE : TRUE;

                //load model
                $this->ci->load->model('address_book_model');

                //save billing address
                if (!$this->ci->address_book_model->save($data, $this->ci->customer->get_id(), NULL, $primary))
                {
                    //log message here
                }
            }

            $shipping_address = $this->ci->shopping_cart->get_shipping_address();

            //create shipping address
            if (isset($shipping_address['create_shipping_address']) && ($shipping_address['create_shipping_address'] == '1'))
            {
                $data['entry_gender'] = $shipping_address['gender'];
                $data['entry_firstname'] = $shipping_address['firstname'];
                $data['entry_lastname'] = $shipping_address['lastname'];
                $data['entry_company'] = $shipping_address['company'];
                $data['entry_street_address'] = $shipping_address['street_address'];
                $data['entry_suburb'] = $shipping_address['suburb'];
                $data['entry_postcode'] = $shipping_address['postcode'];
                $data['entry_city'] = $shipping_address['city'];
                $data['entry_country_id'] = $shipping_address['country_id'];
                $data['entry_zone_id'] = $shipping_address['zone_id'];
                $data['entry_telephone'] = $shipping_address['telephone_number'];
                $data['entry_fax'] = $shipping_address['fax'];

                //load model
                $this->ci->load->model('address_book_model');

                //save billing address
                if (!$this->ci->address_book_model->save($data, $this->ci->customer->get_id()))
                {
                    //log message here
                }
            }
        }

        $this->ci->load->model('order_model');

        $this->ci->order_model->insert_order();
    }
}
// END Order

/* End of file order.php */
/* Location: ./system/tomatocart/libraries/order.php */