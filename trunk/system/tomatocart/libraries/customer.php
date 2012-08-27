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
 * Customer Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	library
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Customer {

    /**
     * ci instance
     *
     * @access private
     * @var string
     */
    protected $ci = NULL;

    /**
     * customers data
     *
     * @access private
     * @var array
     */
    protected $data = array();

    /**
     * Toc Customer Constructor
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        // Grab the customer data array from the session table, if it exists
        if ($this->ci->session->userdata('customer_data') !== FALSE)
        {
            $this->data = $this->ci->session->userdata('customer_data');
        }
        
        log_message('debug', "TOC Customer Class Initialized");
    }

    /**
     * Get customer id
     *
     * @return mixed $value
     */
    public function get_id()
    {
        if (isset($this->data['id']) && is_numeric($this->data['id']))
        {
            return $this->data['id'];
        }

        return FALSE;
    }

    /**
     * Get customer group id
     *
     * @return mixed $value
     */
    public function get_customer_group_id()
    {
        if (isset($this->data['customers_groups_id']) && is_numeric($this->data['customers_groups_id']))
        {
            return $this->data['customers_groups_id'];
        }

        return FALSE;
    }

    /**
     * Get first name
     *
     * @return mixed $value
     */
    public function get_firstname()
    {
        static $first_name = null;

        if (is_null($first_name))
        {
            if (isset($this->data['firstname']))
            {
                $first_name = $this->data['firstname'];
            }
        }

        return $first_name;
    }

    /**
     * Get last name
     *
     * @return mixed $value
     */
    public function get_lastname()
    {
        static $last_name = null;

        if (is_null($last_name))
        {
            if (isset($this->data['lastname']))
            {
                $last_name = $this->data['lastname'];
            }
        }

        return $last_name;
    }

    /**
     * Get name
     *
     * @return mixed $value
     */
    public function get_name()
    {
        static $name = NULL;

        if (empty($name))
        {
            $first_name = empty($this->data['firstname']) ? $this->data['firstname'] : '';
            $last_name = empty($this->data['lastname']) ? $this->data['lastname'] : '';

            $name = $first_name . ' ' . $last_name;
        }

        return $name;
    }

    /**
     * Check whether customer is logged in
     *
     * @return mixed $value
     */
    public function is_logged_on()
    {
        static $logged_on = NULL;

        if (is_null($logged_on))
        {
            $logged_on = isset($this->data['id']) && is_numeric($this->data['id']);
        }

        return $logged_on;
    }

    /**
     * Get gender
     *
     * @return mixed $value
     */
    public function get_gender()
    {
        static $gender = null;

        if (is_null($gender))
        {
            if (isset($this->data['gender']))
            {
                $gender = $this->data['gender'];
            }
        }

        return $gender;
    }

    /**
     * Get email address
     *
     * @return mixed $value
     */
    public function get_email_address()
    {
        static $email_address = null;

        if (is_null($email_address))
        {
            if (isset($this->data['email_address']))
            {
                $email_address = $this->data['email_address'];
            }
        }

        return $email_address;
    }

    /**
     * Get country id
     *
     * @return mixed $value
     */
    public function get_country_id()
    {
        static $country_id = null;

        if (is_null($country_id))
        {
            if (isset($this->data['country_id']))
            {
                $country_id = $this->data['country_id'];
            }
        }

        return $country_id;
    }

    /**
     * Get zone id
     *
     * @return mixed $value
     */
    public function get_zone_id()
    {
        static $zone_id = null;

        if (is_null($zone_id))
        {
            if (isset($this->data['zone_id']))
            {
                $zone_id = $this->data['zone_id'];
            }
        }

        return $zone_id;
    }

    /**
     * Check whether has default address
     *
     * @return boolean
     */
    public function has_default_address() {
        if (isset($this->data['default_address_id']) && is_numeric($this->data['default_address_id'])) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Get default address id
     *
     * @return int
     */
    public function get_default_address_id()
    {
        static $id = null;

        if (is_null($id))
        {
            if (isset($this->data['default_address_id']))
            {
                $id = $this->data['default_address_id'];
            }
        }

        return $id;
    }

    /**
     * Get customer group discount
     *
     * @return float $value
     */
    public function get_customer_group_discount()
    {
        static $customers_groups_discount = null;

        if (is_null($customers_groups_discount))
        {
            if (isset($this->data['customers_groups_discount']))
            {
                $customers_groups_discount = $this->data['customers_groups_discount'];
            }
        }

        return $customers_groups_discount;
    }

    /**
     * Perform login action and assign email and passwrod data.
     *
     * @param string $email
     * @param string $password
     */
    public function set_data($email)
    {
        $data = $this->ci->account_model->get_data($email);

        //if customer data is not null
        if ($data !== FALSE)
        {
            $this->data = array();

            $this->data['id'] = $data['customers_id'];
            $this->data['gender'] = $data['customers_gender'];
            $this->data['firstname'] = $data['customers_firstname'];
            $this->data['lastname'] = $data['customers_lastname'];
            $this->data['email_address'] = $data['customers_email_address'];
            $this->data['default_address_id'] = $data['customers_default_address_id'];
            $this->data['customers_groups_id'] = $data['customers_groups_id'];
            $this->data['credits'] = $data['customers_credits'];
            $this->data['customers_groups_discount'] = $data['customers_groups_discount'];
            $this->data['country_id'] = $data['entry_country_id'];
            $this->data['zone_id'] = $data['entry_zone_id'];
            
            //set data to session
            $this->ci->session->set_userdata('customer_data', $this->data);
        }
        //if user data is not found and session is not empty then reset session
        else if ($this->ci->session->userdata('customer_data') !== FALSE)
        {
            $this->reset();
        }
    }

    /**
     * Synchronize customer data with session.
     */
    public function synchronize_customer_data_with_session()
    {
        //if customer data is not empty
        if (sizeof($this->data) > 0)
        {
            $this->ci->session->set_userdata('customer_data', $this->data);
        }
        //if user data is not found and session is not empty then reset session
        else if ($this->ci->session->userdata('customer_data') !== FALSE)
        {
            $this->reset();
        }
    }

    /**
     * Get store credit data from database.
     */
    public function synchronize_store_credit_with_database()
    {
        if ($this->is_logged_on() === TRUE)
        {
            $store_credit = $this->account_model->get_store_credit();

            if ($store_credit !== FALSE)
            {
                $this->data['customers_credits'] = $store_credit;
            }
        }
    }
    
    /**
     * set the country
     * 
     * @access public
     * @param int
     */
    public function set_country($country_id)
    {
        $this->data['country_id'] = $country_id;
        
        //update data in session
        $this->update_session_data();
    }
    
    /**
     * set the zone
     * 
     * @access public
     * @param int
     */
    public function set_zone($zone_id)
    {
        $this->data['zone_id'] = $zone_id;
        
        //update data in session
        $this->update_session_data();
    }
    
    /**
     * set the default address
     * 
     * @access public
     * @param int
     */
    public function set_default_address($address_book_id)
    {
        if (is_numeric($address_book_id) && ($address_book_id > 0))
        {
            $this->data['default_address_id'] = $address_book_id;
            
            //update data in session
            $this->update_session_data();
        }
    }
    
     
    /**
     * Update the session data
     * 
     * @access public
     */
    public function update_session_data()
    {
        if ($this->ci->session->userdata('customer_data') !== FALSE)
        {
            //update data in session
            $this->ci->session->set_userdata('customer_data', $this->data);
        }
    }
    
    /**
     * Reset customer & session data
     */
    public function reset()
    {
        //clean customer data
        $this->data = array();

        //clean session data
        $this->ci->session->unset_userdata('customer_data');
    }
}
// END Customer Class

/* End of file customer.php */
/* Location: ./system/tomatocart/libraries/customer.php */