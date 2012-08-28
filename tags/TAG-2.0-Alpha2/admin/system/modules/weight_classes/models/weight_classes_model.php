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
 * @filesource modules/weight_classes/models/weight_classes.php
 */

class Weight_Classes_Model extends CI_Model
{
  public function get_classes($start, $limit)
  {
    $Qclasses = $this->db
    ->select('weight_class_id, weight_class_key, weight_class_title')
    ->from('weight_classes')
    ->where('language_id', lang_id())
    ->order_by('weight_class_title')
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qclasses->result_array();
  }
  
  public function get_rules()
  {
    $Qrules = $this->db
    ->select('weight_class_id, weight_class_title')
    ->from('weight_classes')
    ->where('language_id', lang_id())
    ->order_by('weight_class_title')
    ->get();
    
    return $Qrules->result_array();
  }
  
  public function save($id = NULL, $data, $default = FALSE)
  {
    $error = FALSE;
    
    $this->db->trans_begin();
    
    if (is_numeric($id))
    {
      $weight_class_id = $id;
    }
    else
    {
      $Qwc = $this->db
      ->select_max('weight_class_id')
      ->from('weight_classes')
      ->get();
      
      $max_weight_class = $Qwc->row_array();
      
      $Qwc->free_result();
      
      $weight_class_id = $max_weight_class['weight_class_id'] + 1;
    }
    
    foreach(lang_get_all() as $l)
    {
      if (is_numeric($id))
      {
        $this->db->update('weight_classes', 
                          array('weight_class_key' => $data['key'][$l['id']], 
                                'weight_class_title' => $data['name'][$l['id']]),
                          array('weight_class_id' => $weight_class_id, 
                                'language_id' => $l['id']));
      }
      else
      {
        $this->db->insert('weight_classes', array('weight_class_id' => $weight_class_id, 
                                                  'language_id' => $l['id'], 
                                                  'weight_class_key' => $data['key'][$l['id']], 
                                                  'weight_class_title' => $data['name'][$l['id']]));
      }
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
        break;
      }
    }
    
    //handler rules
    if ($error === FALSE)
    {
      if (is_numeric($id))
      {
        $Qrules = $this->db
        ->select('weight_class_to_id')
        ->from('weight_classes_rules')
        ->where(array('weight_class_from_id' => $weight_class_id, 'weight_class_to_id !=' => $weight_class_id))
        ->get();
        
        if ($Qrules->num_rows() > 0)
        {
          foreach($Qrules->result_array() as $rule)
          {
            $this->db->update('weight_classes_rules', 
                              array('weight_class_rule' => $data['rules'][$rule['weight_class_to_id']]), 
                              array('weight_class_from_id' => $weight_class_id, 
                                    'weight_class_to_id' => $rule['weight_class_to_id']));
                              
            if ($this->db->trans_status() === FALSE)
            {
              $error = TRUE;
              break;
            }
          }
        }
        
        $Qrules->free_result();
      }
      else
      {
        $Qclasses = $this->db
        ->select('weight_class_id')
        ->from('weight_classes')
        ->where(array('weight_class_id !=' => $weight_class_id, 'language_id' => lang_id()))
        ->get();
        
        if ($Qclasses->num_rows() > 0)
        {
          foreach($Qclasses->result_array() as $class)
          {
            $this->db->insert('weight_classes_rules', 
                              array('weight_class_from_id' => $class['weight_class_id'], 
                                    'weight_class_to_id' => $weight_class_id, 
                                    'weight_class_rule' => '1'));
                              
            if ($this->db->trans_status() === FALSE)
            {
              $error = TRUE;
              break;
            }
            
            if ($error === FALSE)
            {
              $this->db->insert('weight_classes_rules', 
                                array('weight_class_from_id' => $weight_class_id, 
                                      'weight_class_to_id' => $class['weight_class_id'], 
                                      'weight_class_rule' => $data['rules'][$class['weight_class_id']]));
                                
              if ($this->db->trans_status() === FALSE)
              {
                $error = TRUE;
                break;
              }
            }
          }
        }
        
        $Qclasses->free_result();
      }
    }
    
    //handle configuration
    if ($error === FALSE)
    {
      if ($default === TRUE)
      {
        $this->db->update('configuration', 
                          array('configuration_value' => $weight_class_id), 
                          array('configuration_key' => 'SHIPPING_WEIGHT_UNIT'));
                          
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
        }
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->trans_commit();
      
      if ($default === TRUE && $this->cache->get('configuration'))
      {
        $this->cache->delete('configuration');
      }
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_infos($id)
  {
    $Qwc = $this->db
    ->select('language_id, weight_class_id, weight_class_key, weight_class_title')
    ->from('weight_classes')
    ->where('weight_class_id', $id)
    ->get();
    
    return $Qwc->result_array();
  }
  
  public function get_rules_infos($id)
  {
    $Qrules = $this->db
    ->select('r.weight_class_to_id, r.weight_class_rule, c.weight_class_title, c.weight_class_key')
    ->from('weight_classes_rules r')
    ->join('weight_classes c', 'r.weight_class_to_id = c.weight_class_id')
    ->where(array('r.weight_class_from_id' => $id, 'r.weight_class_to_id !=' => $id, 'c.language_id' => lang_id()))
    ->order_by('c.weight_class_title')
    ->get();
    
    return $Qrules->result_array();
  }
  
  public function delete($id)
  {
    $this->db->trans_begin();
    
    $this->db->where('weight_class_from_id', $id)->or_where('weight_class_to_id', $id)->delete('weight_classes_rules');
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('weight_classes', array('weight_class_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      if ($this->cache->get('weight-classes'))
      {
        $this->cache->delete('weight-classes');
      }
      
      if ($this->cache->get('weight-rules'))
      {
        $this->cache->delete('weight-rules');
      }
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_products($weight_classes_id)
  {
    return $this->db->where('products_weight_class', $weight_classes_id)->from('products')->count_all_results();
  }
  
  
  public function get_total()
  {
    return $this->db->where('language_id', lang_id())->from('weight_classes')->count_all_results();
  }
}

/* End of file weight_classes.php */
/* Location: ./system/modules/weight_classes/models/weight_classes.php */