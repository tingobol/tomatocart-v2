<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Account Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-account-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Address_Book_Model extends CI_Model
{
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Save the address book
     *
     * @access public
     * 
     * @param array
     * @param int
     * @return bool
     */
    public function save($data, $customers_id, $id = NULL, $primary = FALSE)
    {
        $result = FALSE;
        
        //update or insert the address book
        if (is_numeric($id))
        {
            $result = $this->db->update('address_book', $data, array('address_book_id' => $id, 'customers_id' => $customers_id));
        }
        else
        {
            $data['customers_id'] = $customers_id;
            $result = $this->db->insert('address_book', $data);
        }
        
        //set the primary address
        if ($primary === TRUE)
        {
            if (is_numeric($id) === FALSE)
            {
                $id = $this->db->insert_id();
            }
            
            if ($this->set_primary_address($id, $customers_id))
            {
                //update the customer data
                $this->customer->set_country($data['entry_country_id']);
                $this->customer->set_zone($data['entry_zone_id']);
                $this->customer->set_default_address($id);
                
                $result = TRUE;
            }
        }
        
        return $result;
    }
    
    /**
     * Set the primary address
     *
     * @access public
     * 
     * @param int
     * @return bool
     */
    public function set_primary_address($id, $customers_id)
    {
        if (is_numeric($id) && ($id > 0))
        {
            return $this->db->update('customers', array('customers_default_address_id' => $id), array('customers_id' => $customers_id));
        }
        
        return FALSE;
    }
    
    /**
     * Get the the address of the customer
     *
     * @access public
     * @param int
     * @param int
     * @return array
     */
    public function get_address($customers_id, $address_book_id)
    {
        $result = $this->db->select('ab.address_book_id, ab.entry_gender as gender, ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_postcode as postcode, ab.entry_city as city, ab.entry_zone_id as zone_id, ab.entry_telephone as telephone, z.zone_code as zone_code, z.zone_name as zone_name, ab.entry_country_id as country_id, c.countries_name as countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format, ab.entry_state as state, ab.entry_fax as fax')
        ->from('address_book as ab')
        ->join('zones as z', 'ab.entry_zone_id = z.zone_id', 'left')
        ->join('countries as c', 'ab.entry_country_id = c.countries_id', 'left')
        ->where('ab.customers_id', $customers_id)
        ->where('ab.address_book_id', $address_book_id)
        ->get();

        $address_book = FALSE;
        if ($result->num_rows() > 0)
        {
            $address_book = $result->row_array();
        }
        
        return $address_book;
    }
    
    /**
     * Get format of the address book
     *
     * @access public
     * @param mixed
     * @param string
     * @return string
     */
    public function format($address, $new_line = "\n")
    {
        $address_format = $address['address_format'];
        if (empty($address_format)) 
        {
            $address_format = ":name\n:street_address\n:postcode :city\n:country";
        }
        
        $find_array = array('/\:name\b/',
                            '/\:street_address\b/',
                            '/\:suburb\b/',
                            '/\:city\b/',
                            '/\:postcode\b/',
                            '/\:state\b/',
                            '/\:zone_code\b/',
                            '/\:country\b/');
        
        $replace_array = array(output_string_protected($address['firstname'] . ' ' . $address['lastname']),
                               output_string_protected($address['street_address']),
                               output_string_protected($address['suburb']),
                               output_string_protected($address['city']),
                               output_string_protected($address['postcode']),
                               output_string_protected($address['zone_name']),
                               output_string_protected($address['zone_code']),
                               output_string_protected($address['countries_name']));
                               
        $formated = preg_replace($find_array, $replace_array, $address_format);
        
        if ((config('ACCOUNT_COMPANY') > -1) && !empty($address['company'])) 
        {
            $formated = output_string_protected($address['company']) . $new_line . $formated;
        }
        
        if ($new_line != "\n") 
        {
            $formated = str_replace("\n", $new_line, $formated);
        }
        
        return $formated;
    }

    /**
     * Get the the addresses of the customer
     *
     * @access public
     * @param int
     * @return array
     */
    public function get_addresses($customers_id) 
    {
        $result = $this->db->select('ab.address_book_id, ab.entry_gender as gender, ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_postcode as postcode, ab.entry_city as city, ab.entry_zone_id as zone_id, ab.entry_telephone as telephone, z.zone_code as zone_code, z.zone_name as zone_name, ab.entry_country_id as country_id, c.countries_name as countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format, ab.entry_state as state, ab.entry_fax as fax')
        ->from('address_book as ab')
        ->join('zones as z', 'ab.entry_zone_id = z.zone_id', 'left')
        ->join('countries as c', 'ab.entry_country_id = c.countries_id', 'left')
        ->where('ab.customers_id', $customers_id)
        ->get();

        $address_books = array();
        if ($result->num_rows() > 0)
        {
            foreach ($result->result_array() as $row)
            {
                $address_books[] = $row;
            }
        }

        return $address_books;
    }

    /**
     * Get the country data
     *
     * @access public
     * @param int
     * @return array
     */
    public function get_country_data($countries_id)
    {
        $result = $this->db->select('countries_name, countries_iso_code_2, countries_iso_code_3, address_format')->from('countries')->where('countries_id', $countries_id)->get();

        $country = FALSE;
        if ($result->num_rows() > 0)
        {
            $country = $result->row_array();
        }

        return $country;
    }

    /**
     * Get the zone data
     *
     * @access public
     * @param int
     * @return array
     */
    public function get_zone_data($zone_id)
    {
        $result = $this->db->select('zone_code, zone_name')->from('zones')->where('zone_id', $zone_id)->get();

        $zone = FALSE;
        if ($result->num_rows() > 0)
        {
            $zone = $result->row_array();
        }

        return $zone;
    }
    
    /**
     * Get the number of address books for current customer
     *
     * @access public
     * @return int
     */
    public function number_of_entries($customers_id)
    {
        static $total_entries;
        
        if (!is_numeric($total_entries))
        {
            $total_entries = $this->db->from('address_book')->where('customers_id', $customers_id)->count_all_results();
        }
        
        return $total_entries;
    }
    
    /**
     * delete the address book
     *
     * @access public
     * @param int
     * @param int
     * @return bool
     */
    public function delete($address_book_id, $customers_id)
    {
        return $this->db->delete('address_book', array('address_book_id' => $address_book_id, 'customers_id' => $customers_id));
    }
    
    /**
     * check the address book
     *
     * @access public
     * @param int
     * @param int
     * @return bool
     */
    public function check($address_book_id, $customers_id)
    {
        $result = $this->db
        ->select('address_book_id')
        ->from('address_book')
        ->where(array('address_book_id' => $address_book_id, 'customers_id' => $customers_id))
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
}
/* End of file address_book_model.php */
/* Location: ./application/models/address_book_model.php */