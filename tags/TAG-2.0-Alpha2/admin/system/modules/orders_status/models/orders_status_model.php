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
 * @filesource ./system/modules/orders_status/models/orders_status_model.php
 */

class Orders_Status_Model extends CI_Model
{
  public function get_orders_status($start, $limit)
  {
    $Qstatuses = $this->db
    ->select('orders_status_id, orders_status_name, public_flag')
    ->from('orders_status')
    ->where('language_id', lang_id())
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qstatuses->result_array();
  }
  
  public function get_total()
  {
    return $this->db->where('language_id', lang_id())->from('orders_status')->count_all_results();
  }
  
  public function set_status($orders_status_id, $flag)
  {
    $this->db->update('orders_status', array('public_flag' => $flag), 
                                       array('orders_status_id' => $orders_status_id));
                                       
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function save($id = NULL, $data, $default = FALSE)
  {
    $error = FALSE;
    
    $this->db->trans_begin();
    
    if (is_numeric($id))
    {
      $orders_status_id = $id;
    }
    else
    {
      $Qstatus = $this->db->
      select_max('orders_status_id')
      ->from('orders_status')
      ->get();
      
      $status = $Qstatus->row_array();
      
      $orders_status_id = $status['orders_status_id'] + 1;
    }
    
    foreach(lang_get_all() as $l)
    {
      if (is_numeric($id))
      {
        $this->db->update('orders_status', 
                          array('orders_status_name' => $data['name'][$l['id']], 
                                'public_flag' => $data['public_flag']), 
                          array('orders_status_id' => $orders_status_id, 
                                'language_id' => $l['id']));
      }
      else
      {
        $this->db->insert('orders_status', array('orders_status_id' => $orders_status_id, 
                                                 'language_id' => $l['id'], 
                                                 'orders_status_name' => $data['name'][$l['id']], 
                                                 'public_flag' => $data['public_flag']));
      }
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
        break;
      }
    }
    
    if ($error === FALSE)
    {
      if ($default === TRUE)
      {
        $this->db->update('configuration', array('configuration_value' => $orders_status_id), 
                                           array('configuration_key' => 'DEFAULT_ORDERS_STATUS_ID'));
                                           
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
        }
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->trans_commit();
      
      if ($default === TRUE)
      {
        if ($this->cache->get('configuration'))
        {
          $this->cache->delete('configuration');
        }
      }
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function load_order_status($id)
  {
    $Qstatus = $this->db
    ->select('language_id, orders_status_name, public_flag')
    ->from('orders_status')
    ->where('orders_status_id', $id)
    ->get();
    
    return $Qstatus->result_array();
  }
  
  public function check_orders($id)
  {
    return $this->db->where('orders_status', $id)->from('orders')->count_all_results();
  }
  
  public function check_history($id)
  {
    return $this->db->where('orders_status_id', $id)->from('orders_status_history')->group_by('orders_id')->count_all_results();
  }
  
  public function delete($id)
  {
    $this->db->delete('orders_status', array('orders_status_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
}


/* End of file orders_status.php */
/* Location: ./system/modules/orders_status/models/orders_status_model.php */