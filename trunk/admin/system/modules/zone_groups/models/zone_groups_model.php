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
 * @filesource .system/modules/zone_groups/models/zone_groups_model.php
 */

class Zone_Groups_Model extends CI_Model
{
  public function get_geo_zones($start, $limit)
  {
    $Qzones = $this->db
    ->select('*')
    ->from('geo_zones')
    ->order_by('geo_zone_name')
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qzones->result_array();
  }
  
  public function get_entries($geo_zone_id)
  {
    return $this->db->where('geo_zone_id', $geo_zone_id)->from('zones_to_geo_zones')->count_all_results();
  }
  
  public function get_zone_entries_info($geo_zone_id)
  {
    $Qentries_info = $this->db
    ->select('z2gz.association_id, z2gz.zone_country_id countries_id, c.countries_name, z2gz.zone_id, z2gz.geo_zone_id, z2gz.last_modified, z2gz.date_added, z.zone_name')
    ->from('zones_to_geo_zones z2gz')
    ->join('countries c', 'z2gz.zone_country_id = c.countries_id', 'left')
    ->join('zones z', 'z2gz.zone_id = z.zone_id', 'left')
    ->where('z2gz.geo_zone_id', $geo_zone_id)
    ->order_by('c.countries_name, z.zone_name')
    ->get();
    
    return $Qentries_info->result_array();
  }
  
  public function get_countries()
  {
    $Qentries = $this->db
    ->select('countries_name,countries_id')
    ->from('countries')
    ->get();
    
    return $Qentries->result_array();
  }
  
  public function get_zones($id)
  {
    $Qentries = $this->db
    ->select('zone_id,zone_name')
    ->from('zones')
    ->where('zone_country_id', $id)
    ->get();
    
    return $Qentries->result_array();
  }
  
  public function save_entry($id = NULL, $data)
  {
    if (is_numeric($id))
    {
      $this->db->update('zones_to_geo_zones', 
                        array('zone_country_id' => $data['country_id'], 
                              'zone_id' => $data['zone_id'], 
                              'last_modified' => date('Y-m-d H:i:s')), 
                        array('association_id' => $id));
    }
    else
    {
      $this->db->insert('zones_to_geo_zones', 
                        array('zone_country_id' => $data['country_id'], 
                              'zone_id' => $data['zone_id'],
                              'geo_zone_id' => $data['group_id'],
                              'date_added' => date('Y-m-d H:i:s')));
    }
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function delete_entry($id)
  {
    $this->db->delete('zones_to_geo_zones', array('association_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_entry_data($id)
  {
    $Qentries = $this->db
    ->select('z2gz.*, c.countries_name, z.zone_name')
    ->from('zones_to_geo_zones z2gz')
    ->join('countries c', 'z2gz.zone_country_id = c.countries_id', 'left')
    ->join('zones z', 'z2gz.zone_id = z.zone_id', 'left')
    ->where('z2gz.association_id', $id)
    ->get();
    
    $data = $Qentries->row_array();
    
    $Qentries->free_result();
    
    if (empty($data['countries_name'])) 
    {
      $data['countries_name'] = lang('all_countries');
    }
    
    if (empty($data['zone_name'])) 
    {
      $data['zone_name'] = lang('all_zones');
    }
    
    return $data;
  }
  
  public function save($id = NULL, $data)
  {
    if (is_numeric($id))
    {
      $this->db->update('geo_zones', 
                        array('geo_zone_name' => $data['zone_name'], 
                              'geo_zone_description' => $data['zone_description'], 
                              'last_modified' => date('Y-m-d H:i:s')), 
                        array('geo_zone_id' => $id));
    }
    else
    {
      $this->db->insert('geo_zones', 
                        array('geo_zone_name' => $data['zone_name'], 
                              'geo_zone_description' => $data['zone_description'], 
                              'date_added' => date('Y-m-d H:i:s')));
    }
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_data($geo_zone_id, $key = NULL)
  {
    $Qzones = $this->db
    ->select('*')
    ->from('geo_zones')
    ->where('geo_zone_id', $geo_zone_id)
    ->get();
    
    $data = $Qzones->row_array();
    
    $Qzones->free_result();
    
    $data['total_entries'] = $this->db->where('geo_zone_id', $geo_zone_id)->from('zones_to_geo_zones')->count_all_results();
    
    if (empty($key))
    {
      return $data;
    }
    
    return $data[$key];
  }
  
  public function get_tax_rates($geo_zone_id)
  {
    $Qcheck = $this->db
    ->select('tax_zone_id')
    ->from('tax_rates')
    ->where('tax_zone_id', $geo_zone_id)
    ->limit(1)
    ->get();
    
    return $Qcheck->num_rows();
  }
  
  public function delete($id)
  {
    $this->db->trans_begin();
    
    $this->db->delete('zones_to_geo_zones', array('geo_zone_id' => $id));
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('geo_zones', array('geo_zone_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_total()
  {
    return $this->db->count_all('geo_zones');
  }
}

/* End of file zone_groups_model.php */
/* Location: ./system/modules/zone_groups/models/zone_groups_model.php */