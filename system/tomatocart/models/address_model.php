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
 * Address Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-address-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Address_Model extends CI_Model
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
     * Get countries
     *
     * @return array
     */
    public function get_countries()
    {
        $result = $this->db->select('*')->from('countries')->order_by('countries_name')->get();
    
        $countries = array();
        if ($result->num_rows() > 0)
        {
          foreach ($result->result_array() as $row)
          {
            $countries[] = array('id' => $row['countries_id'],
                                 'name' => $row['countries_name'],
                                 'iso_2' => $row['countries_iso_code_2'],
                                 'iso_3' => $row['countries_iso_code_3'],
                                 'format' => $row['address_format']);
          }
        }
    
        return $countries;
    }

    /**
     * Get states
     *
     * @access public
     * @param int
     * @return array
     */
    public function get_states($countries_id)
    {
        $result = $this->db->select('zone_code, zone_name')->from('zones')->where('zone_country_id', $countries_id)->order_by('zone_name')->get();
    
        $states = array();
        if ($result->num_rows() > 0)
        {
          foreach ($result->result_array() as $row)
          {
            $states[] = array('id' => $row['zone_code'], 'text' => $row['zone_name']);
          }
        }
    
        return $states;
    }
    
    /**
     * Check zone id
     * 
     * @access public
     * @param int
     * @return array
     */
    public function check_zone_id($countries_id) 
    {
        $result = $this->db->select('zone_id')->from('zones')->where('zone_country_id', $countries_id)->get();
    
        return ($result->num_rows() > 0) ? TRUE : FALSE;
    }
    
    /**
     * Get zone id
     * 
     * @access public
     * @param int
     * @param string
     * @return int
     */
    public function get_zone_id($countries_id, $state) 
    {
        $zone_id = FALSE;
    
        $result = $this->db->select('zone_id')->from('zones')->where('zone_country_id', $countries_id)->where('zone_code', $state)->get();
    
        if ($result->num_rows() > 0){
          $row = $result->row_array();
          $zone_id = $row['zone_id'];
        } else {
          $result = $this->db->select('zone_id')->from('zones')->where('zone_country_id', $countries_id)->where('zone_code', $state, 'after')->get();
    
          if ($result->num_rows() > 0){
            $row = $result->row_array();
            $zone_id = $row['zone_id'];
          }
        }
    
        return $zone_id;
    }
    
    /**
     * Get zone id
     * 
     * @access public
     * @param int
     * @param int
     * @return array
     */
    public function get_zone_id_via_geo_zone($country_id, $geo_zone_id) 
    {
        $result = $this->db->select('zone_id')->from('zones_to_geo_zones')->where('zone_country_id', $country_id)->where('geo_zone_id', $geo_zone_id)->get();
    
        $zones = FALSE;
        if ($result->num_rows() > 0)
        {
          foreach ($result->result_array() as $row)
          {
            $zones[] = $row['zone_id'];
          }
        }
    
        return $zones;
    }
}
/* End of file address_model.php */
/* Location: ./system/tomatocart/models/address_model.php */