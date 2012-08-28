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

class Countries_Model extends CI_Model
{
  public function get_countries($start, $limit)
  {
    $Qcountries = $this->db
    ->select('countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format')
    ->from('countries')
    ->limit($limit, $start > 0 ? $start -1 : $start)
    ->get();
    
    return $Qcountries->result_array();
  }
  
  public function get_total_zones($id)
  {
    return $this->db->where('zone_country_id', $id)->from('zones')->count_all_results();
  }
  
  public function get_zones($id)
  {
    $Qzones = $this->db
    ->select('zone_id,zone_code,zone_name')
    ->from('zones')
    ->where('zone_country_id', $id)
    ->get();
    
    return $Qzones->result_array();
  }
  
  public function check_address_book($id)
  {
    return $this->db->where('entry_country_id', $id)->from('address_book')->count_all_results();
  }
  
  public function check_geo_zones($id)
  {
    return $this->db->where('zone_country_id', $id)->from('zones_to_geo_zones')->count_all_results();
  }
  
  public function delete($id)
  {
    $this->db->trans_begin();
    
    $this->db->delete('zones', array('zone_country_id' => $id));
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('countries', array('countries_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_zone_address_books($zone_id)
  {
    return $this->db->where('entry_zone_id', $zone_id)->from('address_book')->count_all_results();
  }
  
  public function get_zone_geo_zones($zone_id)
  {
    return $this->db->where('zone_id', $zone_id)->from('zones_to_geo_zones')->count_all_results();
  }
  
  public function delete_zone($zone_id)
  {
    $this->db->delete('zones', array('zone_id' => $zone_id));
    
    if ($this->db->affected_rows() >0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_delete_zones($zones_ids)
  {
    $Qzones = $this->db
    ->select('zone_id, zone_name, zone_code')
    ->from('zones')
    ->where_in('zone_id', $zones_ids)
    ->order_by('zone_name')
    ->get();
    
    return $Qzones->result_array();
  }
  
  public function save($id = NULL, $data)
  {
    if (is_numeric($id) && $id > 0)
    {
      $this->db->update('countries', $data, array('countries_id' => $id));
    }
    else
    {
      $this->db->insert('countries', $data);
    }
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_data($id)
  {
    $Qcountries = $this->db->get('countries', array('countries_id' => $id));
    
    $total_zones = $this->db->where('zone_country_id', $id)->from('zones')->count_all_results();
    
    $data = array_merge($Qcountries->row_array(), array('total_zones' => $total_zones));
    
    $Qcountries->free_result();
    
    return $data;
  }
  
  public function save_zone($id = NULL, $data)
  {
    if (is_numeric($id) && $id > 0)
    {
      $this->db->update('zones', $data, array('zone_id' => $id));
    }
    else
    {
      $this->db->insert('zones', $data);
    }
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_zone_data($id)
  {
    $Qzones = $this->db->get('zones', array('zone_id' => $id));
    
    return $Qzones->row_array();
  }
  
  public function get_totals()
  {
    return $this->db->count_all('countries');
  }
}   