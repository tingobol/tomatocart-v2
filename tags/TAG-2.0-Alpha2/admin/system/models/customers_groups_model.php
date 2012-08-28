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
 * @filesource .system/modules/customers_groups/models/customers_groups_model.php
 */

class Customers_Groups_Model extends CI_Model
{
  public function get_groups($start, $limit)
  {
    $Qgroups = $this->db
    ->select('c.customers_groups_id, cg.language_id, cg.customers_groups_name,  c.customers_groups_discount, c.is_default')
    ->from('customers_groups c')
    ->join('customers_groups_description cg', 'c.customers_groups_id = cg.customers_groups_id')
    ->where('cg.language_id', lang_id())
    ->order_by('cg.customers_groups_name')
    ->limit($limit, $start)
    ->get();
    
    return $Qgroups->result_array();
  }
  
  public function save($id = NULL, $data)
  {
    $error = FALSE;
    
    $this->db->trans_begin();
    
    if (is_numeric($id))
    {
      $this->db->update('customers_groups', 
                        array('customers_groups_discount' => $data['customers_groups_discount'], 'is_default' => $data['is_default']), 
                        array('customers_groups_id' => $id));
    }
    else
    {
      $this->db->insert('customers_groups', array('customers_groups_discount' => $data['customers_groups_discount'], 'is_default' => $data['is_default']));
    }
    
    if ($this->db->trans_status() === FALSE)
    {
      $error = TRUE;
    }
    else
    {
      $group_id = is_numeric($id) ? $id : $this->db->insert_id();
    }
    
    if ($error === FALSE)
    {
      foreach(lang_get_all() as $l)
      {
        if (is_numeric($id))
        {
          $this->db->update('customers_groups_description', 
                            array('customers_groups_name' => $data['customers_groups_name'][$l['id']]), 
                            array('customers_groups_id' => $group_id, 'language_id' => $l['id']));
        }
        else
        {
          $this->db->insert('customers_groups_description', array('customers_groups_id' => $group_id, 
                                                                  'language_id' => $l['id'], 
                                                                  'customers_groups_name' => $data['customers_groups_name'][$l['id']]));
        }
        
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
          break;
        }
      }
    }
    
    if ($error === FALSE)
    {
      if ($data['is_default'] == 1)
      {
        $this->db->update('customers_groups', array('is_default' => 0));
        
        if ($this->db->trans_status() === TRUE)
        {
          $this->db->update('customers_groups', array('is_default' => 1), array('customers_groups_id' => $group_id));
        }
      }
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_data($id)
  {
    $Qgroups = $this->db
    ->select('cg.*, cgd.*')
    ->from('customers_groups cg')
    ->join('customers_groups_description cgd', 'cg.customers_groups_id = cgd.customers_groups_id')
    ->where(array('cg.customers_groups_id' => $id, 'cgd.language_id' => lang_id()))
    ->get();
    
    return $Qgroups->row_array();
  }
  
  public function get_in_use($id)
  {
    return $this->db->from('customers')->where('customers_groups_id', $id)->count_all_results();
  }
  
  public function delete($id)
  {
    $this->db->trans_begin();
    
    $this->db->delete('customers_groups', array('customers_groups_id' => $id));
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('customers_groups_description', array('customers_groups_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_info($id)
  {
    $Qgroups = $this->db
    ->select('c.customers_groups_id, cg.language_id, cg.customers_groups_name,  c.customers_groups_discount, c.is_default')
    ->from('customers_groups c')
    ->join('customers_groups_description cg', 'c.customers_groups_id = cg.customers_groups_id')
    ->where('c.customers_groups_id', $id)
    ->order_by('cg.customers_groups_name')
    ->get();
    
    return $Qgroups->result_array();
  }
  
  public function get_total()
  {
    return $this->db->count_all('customers_groups');
  }
}

/* End of file customers_groups_model.php */
/* Location: .system/modules/customers_groups/models/customers_groups_model.php */