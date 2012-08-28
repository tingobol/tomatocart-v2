<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource
 */

class Mod_Currencies_Model extends CI_Model
{
  public function get_currencies($start, $limit)
  {
    $Qcurrencies = $this->db
    ->select('*')
    ->from('currencies')
    ->order_by('title')
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qcurrencies->result_array();
  }
  
  public function codeIsExist($code)
  {
    $check = $this->db->where('code', $code)->from('currencies')->count_all_results();
    
    if ($check > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
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
        
        if ($this->db->affected_rows() > 0)
        {
          if ($this->cache->get('configuration'))
          {
            $this->cache->delete('configuration');
          }
        }
      }
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      if ($this->cache->get('currencies'))
      {
        $this->cache->delete('currencies');
      }
      
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function delete($currecies_id)
  {
    $Qcheck = $this->db
    ->select('code')
    ->from('currencies')
    ->where('currencies_id', $currecies_id)
    ->get();
    
    $check = $Qcheck->row_array();
    
    if ($check['code'] != DEFAULT_CURRENCY)
    {
      $Qdelete = $this->db->delete('currencies', array('currencies_id' => $currecies_id));
      
      if ($this->db->affected_rows() > 0)
      {
        if ($this->cache->get('currencies'))
        {
          $this->cache->delete('currencies');
        }
        
        return TRUE;
      }
    }
    
    return FALSE;
  }
  
  public function get_data($id)
  {
    $Qcurrencies = $this->db
    ->select('*')
    ->from('currencies')
    ->where('currencies_id', $id)
    ->get();
    
    return $Qcurrencies->row_array();
  }
  
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
  
  public function update_rates($currencies_id, $service)
  {
    $Qcurrency = $this->db
    ->select('currencies_id, code, title')
    ->from('currencies')
    ->where('currencies_id', $currencies_id)
    ->get();
    
    $currency = $Qcurrency->row_array();
    $rate = call_user_func(array($this, 'quote_' . $service . '_currency'), $currency['code']);
    
    return $rate;
    
    if (!empty($rate))
    {
      $this->db->update('currencies', array('value' => $rate, 'last_updated' => date('Y-m-d H:i:s')), array('currencies_id' => $currency['currencies_id']));
      
      if ($this->cache->get('currencies'))
      {
        $this->cache->delete('currencies');
      }
      
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function quote_oanda_currency($code, $base = DEFAULT_CURRENCY)
  {
    $page = file('http://www.oanda.com/convert/fxdaily?value=1&redirected=1&exch=' . $code .  '&format=CSV&dest=Get+Table&sel_list=' . $base);
    
    $match = array();

    preg_match('/(.+),(\w{3}),([0-9.]+),([0-9.]+)/i', implode('', $page), $match);

    if (sizeof($match) > 0) 
    {
      return $match[3];
    } 
    else 
    {
      return FALSE;
    }
  }
  
  public function get_total()
  {
    return $this->db->count_all('currencies');
  }
}

/* End of file currencies_model.php */
/* Location: ./system/modules/currencies/controllers/currencies_model.php */