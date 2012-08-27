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
 * @filesource .system/modules/unit_classes/models/unit_classes_model.php
 */

class Unit_Classes_Model extends CI_Model
{
  public function get_classes($start, $limit)
  {
    $Qclasses = $this->db
    ->select('quantity_unit_class_id,  quantity_unit_class_title')
    ->from('quantity_unit_classes')
    ->where('language_id', lang_id())
    ->limit($limit, $start)
    ->get();
    
    return $Qclasses->result_array();
  }
  
  public function get_total_products($unit_class_id)
  {
    return $this->db->where('quantity_unit_class', $unit_class_id)->from('products')->count_all_results();
  }
  
  public function delete($unit_class_id)
  {
    $this->db->delete('quantity_unit_classes', array('quantity_unit_class_id' => $unit_class_id));
    
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
      $unit_class_id = $id;
    }
    else
    {
      $Qunit = $this->db->select_max('quantity_unit_class_id')->from('quantity_unit_classes')->get();
      
      $max_unit = $Qunit->row_array();
      $Qunit->free_result();
      
      $unit_class_id = $max_unit['quantity_unit_class_id'] + 1;
    }
    
    foreach(lang_get_all() as $l)
    {
      if (is_numeric($id))
      {
        $this->db->update('quantity_unit_classes', 
                          array('quantity_unit_class_title' => $data['unit_class_title'][$l['id']]), 
                          array('quantity_unit_class_id' => $unit_class_id, 'language_id' => $l['id']));
      }
      else
      {
        $this->db->insert('quantity_unit_classes', 
                          array('quantity_unit_class_id' => $unit_class_id, 
                                'language_id' => $l['id'], 
                                'quantity_unit_class_title' => $data['unit_class_title'][$l['id']]));
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
        $this->db->update('configuration', array('configuration_value' => $unit_class_id), array('configuration_key' => 'DEFAULT_UNIT_CLASSES'));
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
  
  public function get_classes_infos($unit_class_id)
  {
    $Qclasses = $this->db
    ->select('language_id, quantity_unit_class_title')
    ->from('quantity_unit_classes')
    ->where('quantity_unit_class_id', $unit_class_id)
    ->get();
    
    return $Qclasses->result_array();
  }
  
  public function get_total()
  {
    return $this->db->where('language_id', lang_id())->from('quantity_unit_classes')->count_all_results();
  }
}

/* End of file unit_classes_model.php */
/* Location: .system/modules/unit_classes/models/unit_classes_model.php */