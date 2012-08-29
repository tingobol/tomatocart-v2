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
 * Currencies Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Currencies_Model extends CI_Model
{
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Get all the currencies
     *
     * @access public
     * @return mixed
     */
    public function get_all()
    {
        $result = $this->db->select('*')->from('currencies')->get();
        
        if ($result->num_rows() > 0)
        {
            $currencies = array();
            foreach($result->result_array() as $row)
            {
                $currencies[$row['code']] = array('id' => $row['currencies_id'],
                                                  'title' => $row['title'],
                                                  'symbol_left' => $row['symbol_left'],
                                                  'symbol_right' => $row['symbol_right'],
                                                  'decimal_places' => $row['decimal_places'],
                                                  'value' => $row['value']);
            }
            
            return $currencies;
        }
    
        return NULL;
    }
  
// ------------------------------------------------------------------------

    /**
     * Get the currencies
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_currencies($start, $limit)
    {
        $result = $this->db
        ->select('*')
        ->from('currencies')
        ->order_by('title')
        ->limit($limit, $start)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Whether the currency code is existed
     *
     * @access public
     * @param $code
     * @return boolean
     */
    public function code_exist($code)
    {
        $result = $this->db->where('code', $code)->from('currencies')->count_all_results();
        
        if ($result > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Save the currency
     *
     * @access public
     * @param $id
     * @param $data
     * @param $set_default
     * @return boolean
     */
    public function save($id = NULL, $data, $set_default = FALSE)
    {
        $this->db->trans_begin();
        
        if (is_numeric($id))
        {
            $this->db->update('currencies', $data, array('currencies_id' => $id));
        }
        else
        {
            $this->db->insert('currencies', $data);
        }
        
        if ($this->db->trans_status() === TRUE)
        {
            $id = is_numeric($id) ? $id : $this->db->insert_id();
            
            if ($set_default === TRUE)
            {
                $this->db->update('configuration', array('configuration_value' => $data['code']), array('configuration_key' => 'DEFAULT_CURRENCY'));
            }
        }
        
        if ($this->db->trans_status() === TRUE)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Delete the currency with its id
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $result = $this->db
        ->select('code')
        ->from('currencies')
        ->where('currencies_id', $id)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            $currency = $result->row_array();
            
            if ($currency['code'] != DEFAULT_CURRENCY)
            {
                $this->db->delete('currencies', array('currencies_id' => $id));
                
                if ($this->db->affected_rows() > 0)
                {
                    return TRUE;
                }
            }
        }
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Get data of the currency
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
        ->select('*')
        ->from('currencies')
        ->where('currencies_id', $id)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
  
    public function get_currencies_info($currecies_ids)
    {
        $Qcurrencies = $this->db
        ->select('currencies_id, title, code')
        ->from('currencies')
        ->where_in('currencies_id', $currecies_ids)
        ->order_by('title')
        ->get();
        
        return $Qcurrencies->result_array();
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Update the currency rate
     *
     * @access public
     * @param $id
     * @param $service
     * @return boolean
     */
    public function update_rates($id)
    {
        $result = $this->db
        ->select('currencies_id, code, title')
        ->from('currencies')
        ->where('currencies_id', $id)
        ->get();
        
        $currency = $result->row_array();
        
        $rate = $this->quote_oanda_currency($currency['code']);
        
        if ($rate !== NULL)
        {
            $this->db->update('currencies', array('value' => $rate, 'last_updated' => date('Y-m-d H:i:s')), array('currencies_id' => $currency['currencies_id']));
            
            if ($this->db->affected_rows() === 1)
            {
                return TRUE; 
            }
        }
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Get the currency rate
     *
     * @access private
     * @param $id
     * @param $base
     * @return $mixed
     */
    private function quote_oanda_currency($code, $base = DEFAULT_CURRENCY)
    {
        $page = file_get_contents('http://www.oanda.com/convert/fxdaily?value=1&redirected=1&exch=' . $base .  '&format=CSV&dest=Get+Table&sel_list=' . $code);
        
        $match = array();
    
        preg_match('/(\w+),(\w{3}),([0-9.]+),([0-9.]+)/i', $page, $match);
        
        if (sizeof($match) > 0) 
        {
            return $match[4];
        } 
            
        return NULL;
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Get the total currencies
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->count_all('currencies');
    }
}

/* End of file currencies_model.php */
/* Location: ./system/models/currencies_model.php */