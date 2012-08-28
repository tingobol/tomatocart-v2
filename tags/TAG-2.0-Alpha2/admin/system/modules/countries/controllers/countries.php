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

class Countries extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('countries_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('countries_main_panel');
    $this->load->view('countries_grid');
    $this->load->view('countries_zones_grid');
    $this->load->view('countries_dialog');
    $this->load->view('countries_zones_dialog');
  }
  
  public function list_countries()
  {
    $this->load->helper('html_output');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $countries = $this->countries_model->get_countries($start, $limit);
    
    $records = array();
    if (!empty($countries))
    {
      foreach($countries as $country)
      {
        $total_zones = $this->countries_model->get_total_zones($country['countries_id']);
        
        $records[] = array('countries_id' => $country['countries_id'], 
                           'countries_name' => $country['countries_name'], 
                           'countries_iso_code' => image('images/worldflags/' . strtolower($country['countries_iso_code_2']) . '.png', $country['countries_name']) . '&nbsp;&nbsp;' . $country['countries_iso_code_2'] . '&nbsp;&nbsp;' . $country['countries_iso_code_3'], 
                           'total_zones' => $total_zones);
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->countries_model->get_totals(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function list_zones()
  {
    $zones = $this->countries_model->get_zones($this->input->get_post('countries_id'));
    
    $records = array();
    if (!empty($zones))
    {
      foreach($zones as $zone)
      {
        $records[] = array('zone_id' => $zone['zone_id'], 
                           'zone_code' => $zone['zone_code'], 
                           'zone_name' => $zone['zone_name']);
      }
    } 
   
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function delete_country()
  {
    $error = false;
    $feedback = array();
     
    $check_address_book = $this->countries_model->check_address_book($this->input->post('countries_id'));
    
    if ($check_address_book > 0)
    {
      $error = TRUE;
      $feedback[] = sprintf(lang('delete_warning_country_in_use_address_book'), $check_address_book);
    }
    
    $check_geo_zones = $this->countries_model->check_geo_zones($this->input->post('countries_id'));
    
    if ($check_geo_zones > 0)
    {
      $error = TRUE;
      $feedback[] = sprintf(lang('delete_warning_country_in_use_tax_zone'), $check_geo_zones);
    }
    
    if ($error === FALSE)
    {
      if ($this->countries_model->delete($this->input->post('countries_id')))
      {
        $response = array('success' => true ,'feedback' => lang('ms_success_action_performed'));
      }
      else
      {
        $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed')); 
      }
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
    }
    
    return $response;
  }
  
  public function delete_zone()
  {
    $error = false;
    $feedback = array();
    
    $address_books = $this->countries_model->get_zone_address_books($this->input->post('zone_id'));
    
    if ($address_books > 0)
    {
      $error = TRUE;
      $feedback[] = sprintf(lang('delete_warning_zone_in_use_address_book'), $address_books);
    }
    
    $geo_zones = $this->countries_model->get_zone_geo_zones($this->input->post('zone_id'));
    
    if ($geo_zones > 0)
    {
      $error = TRUE;
      $feedback[] = sprintf(lang('delete_warning_zone_in_use_tax_zone'), $geo_zones);
    }
    
    if ($error === FALSE)
    {
      if ($this->countries_model->delete_zone($this->input->post('zone_id')))
      {
        $response = array('success' => true ,'feedback' => lang('ms_success_action_performed'));
      }
      else
      {
        $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));    
      }
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
    }
    
    return $response;
  }
  
  public function delete_zones()
  {
    $error = false;
    $feedback = array();
    $check_tax_zones_flag = array();
    $check_address_book_flag = array();
      
    $batch = $this->input->post('batch');
    
    $zones_ids = json_decode($batch);
    
    $zones = $this->countries_model->get_delete_zones($zones_ids);
    
    if (!empty($zones))
    {
      foreach($zones as $zone)
      {
        $address_books = $this->countries_model->get_zone_address_books($zone['zone_id']);
        
        if ($address_books > 0)
        {
          $error = TRUE;
          $check_address_book_flag[] = $zone['zone_name'];
        }
        
        $geo_zones = $this->countries_model->get_zone_geo_zones($zone['zone_id']);
        
        if ($geo_zones > 0)
        {
          $error = TRUE;
          $check_tax_zones_flag[] = $zone['zone_name'];
        }
      }
    }
    
    if (!empty($check_address_book_flag)) 
    {
      $feedback[] = lang('batch_delete_warning_zone_in_use_address_book') . '<p>' . implode(', ', $check_address_book_flag) . '</p>';
    }
    
    if (!empty($check_tax_zones_flag)) 
    {
      $feedback[] = lang('batch_delete_warning_zone_in_use_tax_zone') . '<p>' . implode(', ', $check_tax_zones_flag) . '</p>';
    }

    if ($error === FALSE && !empty($zones_ids))
    {
      foreach($zones_ids as $id)
      {
        if ($this->countries_model->delete_zone($id) === FALSE)
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
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
    }
    
    return $response;
  }
  
  public function save_country()
  {
    $data = array('countries_name' => $this->input->post('countries_name'), 
                  'countries_iso_code_2' => $this->input->post('countries_iso_code_2'), 
                  'countries_iso_code_3' => $this->input->post('countries_iso_code_3'), 
                  'address_format' => $this->input->post('address_format'));
    
    if ($this->countries_model->save($this->input->post('countries_id'), $data))
    {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function load_country()
  {
    $data = $this->countries_model->get_data($this->input->post('countries_id'));
    
    $response = array('success' => TRUE, 'data' => $data);
    
    return $response;
  }
  
  public function save_zone()
  {
    $data = array('zone_name' => $this->input->post('zone_name'), 
                  'zone_code' => $this->input->post('zone_code'), 
                  'zone_country_id' => $this->input->post('countries_id'));
    
    if ($this->countries_model->save_zone($this->input->post('zone_id'), $data))
    {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function load_zone()
  {
    $data = $this->countries_model->get_zone_data($this->input->post('zone_id'));
    
    $response = array('success' => TRUE, 'data' => $data);
    
    return $response;
  }
} 

/* End of file countries.php */
/* Location: ./system/modules/countries/controllers/countries.php */