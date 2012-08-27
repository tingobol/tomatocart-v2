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
 * @filesource .system/modules/zone_groups/controllers/zone_groups.php
 */

Class Zone_Groups extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('zone_groups_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('zone_groups_main_panel');
    $this->load->view('zone_groups_grid');
    $this->load->view('zone_entries_grid');
    $this->load->view('zone_entries_dialog');
    $this->load->view('zone_groups_dialog');
  }
  
  public function list_zone_groups()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $zones = $this->zone_groups_model->get_geo_zones($start, $limit);
    
    $records = array();
    if (!empty($zones))
    {
      foreach($zones as $zone)
      {
        $entries = $this->zone_groups_model->get_entries($zone['geo_zone_id']);
        
        $records[] = array( 'geo_zone_id' => $zone['geo_zone_id'],
                            'geo_zone_name' => $zone['geo_zone_name'],
                            'geo_zone_entries' => $entries);    
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->zone_groups_model->get_total(),
                 EXT_JSON_READER_ROOT => $records); 
  }
  
  public function list_zone_entries()
  {
    $entries = $this->zone_groups_model->get_zone_entries_info($this->input->get_post('geo_zone_id'));
    
    $records = array();
    if (!empty($entries))
    {
      foreach($entries as $entry)
      {
        $records[] = array(
          'geo_zone_entry_id' => $entry['association_id'],
          'countries_id' => $entry['countries_id'],
          'zone_id' => $entry['zone_id'],
          'countries_name' => (($entry['countries_id'] > 0) ? $entry['countries_name'] : lang('all_countries')),
          'zone_name' => (($entry['zone_id'] > 0) ? $entry['zone_name'] : lang('all_zones')));
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function get_countries()
  {
    $entries = $this->zone_groups_model->get_countries();
    
    $records = array(array('countries_id' => '0',
                           'countries_name' => lang('all_countries')));
    
    if (!empty($entries))
    {
      foreach($entries as $entry)
      {
        $records[] = array('countries_id' => $entry['countries_id'],
                           'countries_name' => $entry['countries_name']);
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function get_zones()
  {
    $entries = $this->zone_groups_model->get_zones($this->input->get_post('countries_id'));
    
    $records = array(array('zone_id' => '0',
                           'zone_name' => lang('all_zones')));
      
    if (!empty($entries))
    {
      foreach($entries as $entry)
      {
        $records[] = array('zone_id' => $entry['zone_id'],
                           'zone_name' => $entry['zone_name']);
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function save_zone_entry()
  {
    $data = array('group_id' => $this->input->post('geo_zone_id'), 
                  'country_id' => $this->input->post('countries_id'), 
                  'zone_id' => $this->input->post('zone_id'));
    
    if ($this->zone_groups_model->save_entry($this->input->post('geo_zone_entry_id'), $data))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));  
    }
    
    return $response;
  }
  
  public function delete_zone_entry()
  {
    if ($this->zone_groups_model->delete_entry($this->input->post('geo_zone_entry_id')))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));  
    }
    
    return $response;
  }
  
  public function delete_zone_entries()
  {
    $error = FALSE;
    
    $entries_ids = json_decode($this->input->post('batch'));
    
    if (!empty($entries_ids))
    {
      foreach($entries_ids as $id)
      {
        if ($this->zone_groups_model->delete_entry($id) === FALSE)
        {
          $error = TRUE;
          break;
        }
      }
    }
    
    if ($error === FALSE) 
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    } 
    else 
    {
      $response = array('success' => FALSE ,'feedback' => lang('ms_error_action_not_performed'));               
    }
    
    return $response;
  }
  
  public function load_zone_entry()
  {
    $data = $this->zone_groups_model->get_entry_data($this->input->post('geo_zone_entry_id'));
    
    if (!empty($data))
    {
      $response = array('success' => TRUE, 'data' => $data);
    }
    else
    {
      $response = array('success' => FALSE);
    }
    
    return $response;
  }
  
  public function save_zone_group()
  {
    $data = array('zone_name' => $this->input->post('geo_zone_name'), 
                  'zone_description' => $this->input->post('geo_zone_description'));
    
    if ($this->zone_groups_model->save($this->input->post('geo_zone_id'), $data))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));  
    }
    
    return $response;
  }
  
  public function load_zone_group()
  {
    $data = $this->zone_groups_model->get_data($this->input->post('geo_zone_id'));
    
    return array('success' => TRUE, 'data' => $data);
  }
  
  public function delete_zone_group()
  {
    $error = FALSE;
    $feedback = array();
    
    $check_tax_rates = $this->zone_groups_model->get_tax_rates($this->input->post('geo_zone_id'));
    
    if ($check_tax_rates > 0)
    {
      $error = TRUE;
      $feedback[] = sprintf(lang('delete_warning_group_in_use_tax_rate'), $check_tax_rates);
    }
    
    if ($error === FALSE)
    {
      if ($this->zone_groups_model->delete($this->input->post('geo_zone_id')))
      {
        $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
      }
      else
      {
        $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));    
      }
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
    }
    
    return $response;
  }
}

/* End of file zone_groups.php */
/* Location: ./system/modules/zone_groups/controllers/zone_groups.php */