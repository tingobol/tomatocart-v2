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
 * Account Model
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-model
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Account_Model extends CI_Model
{

    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get customer data via email address
     *
     * @param $email
     * @return array
     */
    public function get_data($email)
    {
        $result = $this->db->select('c.*, ab.address_book_id, ab.entry_gender, ab.entry_company, ab.entry_firstname, ab.entry_lastname, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_state, ab.entry_country_id, ab.entry_zone_id, ab.entry_telephone, ab.entry_fax, cg.customers_groups_discount')
        ->from('customers as c')
        ->join('customers_groups as cg', 'c.customers_groups_id = cg.customers_groups_id', 'left')
        ->join('address_book as ab', 'c.customers_id = ab.customers_id AND ab.address_book_id = c.customers_default_address_id', 'left')
        ->where('customers_email_address', $email)
        ->get();
        
        $data = FALSE;
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();
        }
        
        return $data;
    }
    
    /**
     * Check whether the emails have already been used by other customer
     *
     * @param $email
     * @return bool
     */
    public function check_duplicate_entry($email)
    {
        $result = $this->db
        ->select('customers_id')
        ->from('customers')
        ->where(array('customers_email_address' => $email, 'customers_id !=' => $this->customer->get_id()))
        ->limit(1)
        ->get();
        
        if ($result->num_rows() == 1)
        {
            return TRUE;
        }
        
        return FALSE;
    }

    /**
     * Check user account
     *
     * @param $email email address
     * @param $password user password
     * @return boolean
     */
    public function check_account($email, $password)
    {
        $data = $this->get_data($email);

        if ($data !== FALSE)
        {
            $stack = explode(':', $data['customers_password']);

            if (sizeof($stack) === 2)
            {
                if (md5($stack[1] . $password) == $stack[0])
                {
                    return TRUE;
                }
            }
        }

        return FALSE;
    }
    /**
     * check customer email status
     * 
     * @param string
     * @return boolean
     */
    public function status_check($email)
    {
        $data = $this->get_data($email);

        if ($data !== FALSE)
        {
            return ($data['customers_status'] == '1') ? TRUE : FALSE;
        }
    }

    public function get_store_credit($customers_id)
    {
        $result = $this->db->select('customers_credits')->from('customers')->where('customers_id', $customers_id)->get();

        $store_credit = FALSE;
        if ($result->num_rows() > 0)
        {
            $row = $result->row_array();
            $store_credit = $row['customers_credits'];
        }

        return $store_credit;
    }

    public function get_address($customers_id, $address_id)
    {
        $result = $this->db
        ->select('*')
        ->from('address_book')
        ->where('customers_id', $customers_id)
        ->where('address_book_id', $address_id)
        ->get();

        $data = FALSE;
        if ($query->num_rows() > 0)
        {
            $data = $query->row_array();
        }

        return $data;
    }

    /**
     * Insert customer data
     *
     * @param $data
     */
    public function insert($data)
    {
        return $this->db->insert('customers', $data);
    }
    
    /**
     * Save customer data
     *
     * @param $data
     */
    public function save($data, $customers_id)
    {
        $data['date_account_last_modified'] = now();
        
        return $this->db->update('customers', $data, array('customers_id' => $customers_id));
    }

    /**
     * Update customer last logon
     * 
     * @param int $id
     */
    public function update_last_logon($id) 
    {
        return $this->db->update('customers', array( 'date_last_logon' => 'now', 'number_of_logons' => 'number_of_logons+1'), array('customers_id' => $id));
    }
    
    /**
     * Update customers newsletter
     * 
     * @param string
     * @param int
     */
    public function update_customers_newsletter($newsletter, $customers_id)
    {
        return $this->db->update('customers', array('customers_newsletter' => $newsletter), array('customers_id' => $customers_id));
    }
}

/* End of file account_model.php */
/* Location: ./system/tomatocart/models/account_model.php */