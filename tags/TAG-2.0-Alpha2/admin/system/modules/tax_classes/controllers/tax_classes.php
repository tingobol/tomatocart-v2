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

class Tax_Classes extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('tax_classes_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('tax_classes_main_panel');
    $this->load->view('tax_classes_grid');
    $this->load->view('tax_rates_grid');
    $this->load->view('tax_classes_dialog');
    $this->load->view('tax_rates_dialog');
  }
  
  public function list_tax_classes()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $tax_classes = $this->tax_classes_model->get_tax_classes($start, $limit);
    
    $record = array();
    if (!empty($tax_classes))
    {
      foreach($tax_classes as $tax_class)
      {
        $tax_total_rates = $this->tax_classes_model->get_total_rates($tax_class['tax_class_id']);
        
        $records[] = array(
          'tax_class_id' => $tax_class['tax_class_id'],
          'tax_class_title' => $tax_class['tax_class_title'],
          'tax_total_rates' => $tax_total_rates
        );
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->tax_classes_model->get_total(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function list_tax_rates()
  {
    $tax_rates = $this->tax_classes_model->get_tax_rates($this->input->get_post('tax_class_id'));
    
    $records = array();
    if (!empty($tax_rates))
    {
      foreach($tax_rates as $tax_rate)
      {
        $records[] = array('tax_rates_id' => $tax_rate['tax_rates_id'], 
                           'tax_priority' => $tax_rate['tax_priority'], 
                           'tax_rate' => $tax_rate['tax_rate'], 
                           'geo_zone_name' => $tax_rate['geo_zone_name']);
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function delete_tax_class()
  {
    $error = false;
    $feedback = array();
    
    $check_products = $this->tax_classes_model->get_products($this->input->post('tax_class_id'));
    
    if ($check_products > 0)
    {
      $error = TRUE;
      $feedback[] = sprintf(lang('delete_warning_tax_class_in_use'), $check_products);
    }
    
    if ($error === FALSE)
    {
      if ($this->tax_classes_model->delete($this->input->post('tax_class_id')))
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
  
  public function save_tax_class()
  {
    $data = array('tax_class_title' => $this->input->post('tax_class_title'), 
                  'tax_class_description' => $this->input->post('tax_class_description'));
    
    if ($this->tax_classes_model->save($this->input->post('tax_class_id'), $data))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function list_geo_zones()
  {
    $zones = $this->tax_classes_model->get_zones();
    
    $records = array();
    if (!empty($zones))
    {
      foreach($zones as $zone)
      {
        $records[] = array('geo_zone_id' => $zone['geo_zone_id'], 'geo_zone_name' => $zone['geo_zone_name']);
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function save_tax_rate()
  {
    $data = array('tax_zone_id' => $this->input->post('geo_zone_id'), 
                  'tax_class_id' => $this->input->post('tax_class_id'), 
                  'tax_rate' => $this->input->post('tax_rate'), 
                  'tax_description' => $this->input->post('tax_description'), 
                  'tax_priority' => $this->input->post('tax_priority'));
    
    if ($this->tax_classes_model->save_entry($this->input->post('tax_rates_id'), $data))
    {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function load_tax_class()
  {
    $data = $this->tax_classes_model->get_data($this->input->post('tax_class_id'));
    
    return array('success' => TRUE, 'data' => $data);
  }
  
  public function delete_tax_rate()
  {
    if ($this->tax_classes_model->delete_entry($this->input->post('rateId')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_tax_rates()
  {
    $error = FALSE;
    
    $batch = $this->input->post('batch');
    $tax_rates_ids = json_decode($batch);
    
    foreach($tax_rates_ids as $id)
    {
      if ($this->tax_classes_model->delete_entry($id) === FALSE)
      {
        $error = TRUE;
        break;
      }
    }
    
    if ($error === FALSE)
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function load_tax_rate() 
  {
    $data = $this->tax_classes_model->get_entry_data($this->input->post('tax_rates_id'));
    
    return array('success' => TRUE, 'data' => $data);
  }
}

/* End of file tax_classes.php */
/* Location: ./system/modules/countries/controllers/tax_classes.php */