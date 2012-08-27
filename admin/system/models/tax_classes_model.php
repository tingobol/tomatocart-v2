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

class Tax_Classes_Model extends CI_Model
{
  public function get_tax_classes($start, $limit)
  {
    $Qclasses = $this->db
    ->select('tax_class_id, tax_class_title, tax_class_description, last_modified, date_added')
    ->from('tax_class')
    ->order_by('tax_class_title')
    ->limit($limit, $start)
    ->get();
    
    return $Qclasses->result_array();
  }
  
  public function get_total_rates($tax_class_id)
  {
    return $this->db->where('tax_class_id', $tax_class_id)->from('tax_rates')->count_all_results();
  }
  
  public function get_tax_rates($tax_classes_id)
  {
    $Qrates = $this->db
    ->select('r.tax_rates_id, r.tax_priority, r.tax_rate, r.tax_description, r.date_added, r.last_modified, z.geo_zone_id, z.geo_zone_name')
    ->from('tax_rates r')
    ->join('geo_zones z', 'r.tax_zone_id = z.geo_zone_id')
    ->where('r.tax_class_id', $tax_classes_id)
    ->get();
    
    return $Qrates->result_array();
  }
  
  public function get_data($id)
  {
    $Qclasses = $this->db->get('tax_class', array('tax_class_id' => $id));
    
    $total_rates = $this->db->where('tax_class_id', $id)->from('tax_rates')->count_all_results();
    
    $data = array_merge($Qclasses->row_array(), array('total_tax_rates' => $total_rates));
    
    $Qclasses->free_result();
    
    return $data;
  }
  
  public function get_products($tax_class_id)
  {
    $Qcheck = $this->db
    ->select('products_id')
    ->from('products')
    ->where('products_tax_class_id', $tax_class_id)
    ->limit(1)
    ->get();
    
    return $Qcheck->num_rows();
  }
  
  public function delete($id)
  {
    $this->db->trans_begin();
    
    $Qrates = $this->db->delete('tax_rates', array('tax_class_id' => $id));
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('tax_class', array('tax_class_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function save($id = NULL, $data)
  {
    if (is_numeric($id))
    {
      $data['last_modified'] = date('Y-m-d H:i:s');
      $this->db->update('tax_class', $data, array('tax_class_id' => $id));
    }
    else
    {
      $data['date_added'] = date('Y-m-d H:i:s');
      $this->db->insert('tax_class', $data);
    }
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_zones()
  {
    $Qzones = $this->db
    ->select('geo_zone_id, geo_zone_name')
    ->from('geo_zones')
    ->get();
    
    return $Qzones->result_array();
  }
  
  public function save_entry($id = NULL, $data)
  {
    if (is_numeric($id))
    {
      $data['last_modified'] = date('Y-m-d H:i:s');
      
      $this->db->update('tax_rates', $data, array('tax_rates_id' => $id));
    }
    else
    {
      $data['date_added'] = date('Y-m-d H:i:s');
      
      $this->db->insert('tax_rates', $data);
    }
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function delete_entry($id)
  {
    $Qrate = $this->db->delete('tax_rates', array('tax_rates_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_entry_data($id)
  {
    $Qrates = $this->db
    ->select('r.*, tc.tax_class_title, z.geo_zone_id, z.geo_zone_name')
    ->from('tax_rates r')
    ->join('tax_class tc', 'r.tax_class_id = tc.tax_class_id')
    ->join('geo_zones z', 'r.tax_zone_id = z.geo_zone_id')
    ->where('r.tax_rates_id', $id)
    ->get();
    
    return $Qrates->row_array();
  }
  
  public function get_total()
  {
    return $this->db->count_all('tax_class');
  }
}

/* End of file tax_classes_model.php */
/* Location: ./system/modules/countries/controllers/tax_classes_model.php */