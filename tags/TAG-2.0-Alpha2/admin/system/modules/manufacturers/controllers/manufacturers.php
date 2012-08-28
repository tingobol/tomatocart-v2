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

class Manufacturers extends TOC_Controller 
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('manufacturers_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('manufacturers_grid');
    $this->load->view('manufacturers_general_panel');
    $this->load->view('manufacturers_meta_info_panel');
    $this->load->view('manufacturers_dialog');
  }
  
  public function list_manufacturers()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $manufacturers = $this->manufacturers_model->get_manufacturers($start, $limit);
    
    $records = array();
    if (!empty($manufacturers))
    {
      foreach($manufacturers as $manufacturer)
      {
        $clicked = $this->manufacturers_model->get_sum_clicks($manufacturer['manufacturers_id']);
        
        $records[] = array('manufacturers_id' => $manufacturer['manufacturers_id'], 
                           'manufacturers_name' => $manufacturer['manufacturers_name'], 
                           'url_clicked' => $clicked);
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->manufacturers_model->get_totals(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function delete_manufacturer()
  {
    $this->load->model('products_model');
    $this->load->driver('cache', array('adapter' => 'file'));
    
    if ($this->manufacturers_model->delete($this->input->post('manufacturers_id')))
    {
      $response = array('success' => true ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_manufacturers()
  {
    $this->load->model('products_model');
    $this->load->driver('cache', array('adapter' => 'file'));
    
    $error = FALSE;
    
    $batch = $this->input->post('batch');
    $manufacturers_ids = json_decode($batch);
    
    if (!empty($manufacturers_ids))
    {
      foreach($manufacturers_ids as $manufacturers_id)
      {
        if ($this->manufacturers_model->delete($manufacturers_id) !== TRUE)
        {
          $error = TRUE;
          break;
        }
      }
    }
    else
    {
      $error = TRUE;
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
  
  public function save_manufacturer()
  {
    $this->load->library('upload');
    $this->load->helper('html_output');
    $this->load->driver('cache', array('adapter' => 'file'));
    
    //search engine friendly urls
    $formatted_urls = array();
    $urls = $this->input->post('manufacturers_friendly_url');
    
    if (is_array($urls) && !empty($urls))
    {
      foreach($urls as $languages_id => $url)
      {
        $url = format_friendly_url($url);
        
        if (empty($url))
        {
          $url = format_friendly_url($this->input->post('manufacturers_name'));
        }
        
        $formatted_urls[$languages_id] = $url;
      }
    }
    
    $data = array('name' => $this->input->post('manufacturers_name'),
                  'image' => 'manufacturers_image',
                  'friendly_url' => $formatted_urls,
                  'url' => $this->input->post('manufacturers_url'),
                  'page_title' => $this->input->post('page_title'),
                  'meta_keywords' => $this->input->post('meta_keywords'),
                  'meta_description' => $this->input->post('meta_description'));
    
    if ($this->manufacturers_model->save($this->input->post('manufacturers_id'), $data))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed')); 
    }
    
    $this->output->set_header("Content-Type: text/html")->set_output(json_encode($response));
    
    return NULL;
  }
  
  public function load_manufacturer()
  {
    $data = $this->manufacturers_model->get_data($this->input->post('manufacturers_id'));
    
    $manufacturers_info = $this->manufacturers_model->get_info($this->input->post('manufacturers_id'));
    
    if (!empty($manufacturers_info))
    {
      foreach($manufacturers_info as $manufacturer_info)
      {
        $data['manufacturers_url[' . $manufacturer_info['languages_id'] . ']'] = $manufacturer_info['manufacturers_url'];
        $data['manufacturers_friendly_url[' . $manufacturer_info['languages_id'] . ']'] = $manufacturer_info['manufacturers_friendly_url'];
        $data['page_title[' . $manufacturer_info['languages_id'] . ']'] = $manufacturer_info['manufacturers_page_title'];
        $data['meta_keywords[' . $manufacturer_info['languages_id'] . ']'] = $manufacturer_info['manufacturers_meta_keywords'];
        $data['meta_description[' . $manufacturer_info['languages_id'] . ']'] = $manufacturer_info['manufacturers_meta_description'];
      }
    }
    
    return array('success' => true, 'data' => $data);
  }
}

/* End of file manufacturers.php */
/* Location: ./system/modules/manufacturers/controllers/manufacturers.php */
