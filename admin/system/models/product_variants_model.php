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
 * @filesource ./system/modules/product_variants/models/product_variants_model.php
 */

class Product_Variants_Model extends CI_Model
{
  public function get_variants_groups($start, $limit)
  {
    $Qgroup = $this->db
    ->select('products_variants_groups_id, products_variants_groups_name')
    ->from('products_variants_groups')
    ->where('language_id', lang_id())
    ->order_by('products_variants_groups_name')
    ->limit($limit, $start)
    ->get();
    
    return $Qgroup->result_array();
  }
  
  public function get_total_entries($groups_id)
  {
    return $this->db->where('products_variants_groups_id', $groups_id)->from('products_variants_values_to_products_variants_groups')->count_all_results();
  }
  
  public function get_variants_entries($groups_id)
  {
    $Qentries = $this->db
    ->select('pvv.products_variants_values_id, pvv.products_variants_values_name')
    ->from('products_variants_values pvv')
    ->join('products_variants_values_to_products_variants_groups pvv2pvg', 'pvv2pvg.products_variants_values_id = pvv.products_variants_values_id')
    ->where(array('pvv2pvg.products_variants_groups_id' => $groups_id, 'pvv.language_id' => lang_id()))
    ->order_by('pvv.products_variants_values_name')
    ->get();
    
    return $Qentries->result_array();
  }
  
  public function get_entry_data($id, $language_id = NULL)
  {
    $language_id = empty($language_id) ? lang_id() : $language_id;
    
    $Qentry = $this->db
    ->select('*')
    ->from('products_variants_values')
    ->where(array('products_variants_values_id' => $id, 'language_id' => $language_id))
    ->get();
    
    $data = $Qentry->row_array();
    $Qentry->free_result();
    
    $total_products = $this->db
    ->where('products_variants_values_id', $data['products_variants_values_id'])
    ->from('products_variants_entries')
    ->count_all_results();
    
    $data['total_products'] = $total_products;
    
    return $data;
  }
  
  public function delete_entry($id, $group_id)
  {
    $error = FALSE;
    
    $this->db->trans_begin();
    
    $this->db->delete('products_variants_values', array('products_variants_values_id' => $id));
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('products_variants_values_to_products_variants_groups', 
                        array('products_variants_groups_id' => $group_id, 
                              'products_variants_values_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function save_entry($id, $data)
  {
    $error = FALSE;
    
    if (is_numeric($id))
    {
      $entry_id = $id;
    }
    else
    {
      $Qcheck = $this->db
      ->select_max('products_variants_values_id')
      ->from('products_variants_values')
      ->get();
      
      $max_values = $Qcheck->row_array();
      $entry_id = $max_values['products_variants_values_id'] + 1;
    }
    
    $this->db->trans_begin();
    
    foreach(lang_get_all() as $l)
    {
      if (is_numeric($id))
      {
        $this->db->update('products_variants_values', 
                          array('products_variants_values_name' => $data['name'][$l['id']]), 
                          array('products_variants_values_id' => $entry_id, 'language_id' => $l['id']));
      }
      else
      {
        $this->db->insert('products_variants_values', 
                          array('products_variants_values_id' => $entry_id, 
                                'language_id' => $l['id'], 
                                'products_variants_values_name' => $data['name'][$l['id']]));
      }
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
        break;
      }
    }
    
    if ($error === FALSE)
    {
      if (!is_numeric($id))
      {
        $this->db->insert('products_variants_values_to_products_variants_groups', 
                          array('products_variants_groups_id' => $data['products_variants_groups_id'], 
                                'products_variants_values_id' => $entry_id));
                          
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
        }
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
  
  public function get_entries_data($id)
  {
    $Qentry = $this->db
    ->select('*')
    ->from('products_variants_values')
    ->where('products_variants_values_id', $id)
    ->get();
    
    return $Qentry->result_array();
  }
  
  public function get_groups_data($id)
  {
    $Qgroup = $this->db
    ->select('*')
    ->from('products_variants_groups')
    ->where('products_variants_groups_id', $id)
    ->get();
    
    return $Qgroup->result_array();
  }
  
  public function save($id = NULL, $data)
  {
    $error = FALSE;
    
    if (is_numeric($id))
    {
      $group_id = $id;
    }
    else
    {
      $Qcheck = $this->db
      ->select_max('products_variants_groups_id')
      ->from('products_variants_values_to_products_variants_groups')
      ->get();
      
      $max_groups = $Qcheck->row_array();
      $Qcheck->free_result();
      
      $group_id = $max_groups['products_variants_groups_id'] + 1;
    }
    
    $this->db->trans_begin();
    
    foreach(lang_get_all() as $l)
    {
      if (is_numeric($id))
      {
        $this->db->update('products_variants_groups', 
                          array('products_variants_groups_name' => $data['name'][$l['id']]), 
                          array('products_variants_groups_id' => $group_id, 'language_id' => $l['id']));
      }
      else
      {
        $this->db->insert('products_variants_groups', 
                          array('products_variants_groups_id' => $group_id, 
                                'language_id' => $l['id'], 
                                'products_variants_groups_name' => $data['name'][$l['id']]));
      }
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
        break;
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
  
  public function delete($id)
  {
    $error = FALSE;
    
    $this->db->trans_begin();
    
    $Qentries = $this->db
    ->select('products_variants_values_id')
    ->from('products_variants_values_to_products_variants_groups')
    ->where('products_variants_groups_id', $id)
    ->get();
    
    if ($Qentries->num_rows() > 0)
    {
      foreach($Qentries->result_array() as $entry)
      {
        $this->db->delete('products_variants_values', array('products_variants_values_id' => $entry['products_variants_values_id']));
        
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
          break;
        }
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('products_variants_values_to_products_variants_groups', array('products_variants_groups_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('products_variants_groups', array('products_variants_groups_id' => $id));
      
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
  
  public function get_group_products($groups_id)
  {
    return $this->db->where('products_variants_groups_id', $groups_id)->from('products_variants_entries')->count_all_results();
  }
  
  
  public function get_total()
  {
    return $this->db->where('language_id', lang_id())->from('products_variants_groups')->count_all_results();
  }
}

/* End of file product_variants_model.php */
/* Location: ./system/modules/product_variants/models/product_variants_model.php */