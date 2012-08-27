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
 * @filesource modules/specials/controllers/specials.php
 */

class Specials extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('specials_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('specials_grid');
    $this->load->view('specials_dialog');
  }
  
  public function list_specials()
  {
    $this->load->library('currencies');
    $this->load->library('category_tree');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $current_category_id = end(explode( '_' , $this->input->get_post('category_id') ? $this->input->get_post('category_id') : 0));
    
    $params = array('start' => $start, 
                    'limit' => $limit, 
                    'search' => $this->input->get_post('search'), 
                    'manufacturers_id' => $this->input->get_post('manufacturers_id'));
    
    if ($current_category_id > 0)
    {
      $this->category_tree->set_breadcrumb_usage(FALSE);
      
      $in_categories = array($current_category_id);
      
      foreach($this->category_tree->getTree($current_category_id) as $category)
      {
        $in_categories[] = $category['id'];
      }
      
      $params['in_categories'] = $in_categories;
    }
    
    $specials = $this->specials_model->get_specials($params);
    
    $records = array();
    if (!empty($specials))
    {
      foreach($specials as $special)
      {
        $new_price = array('specials_new_products_price' => '<span class="oldPrice">' . $this->currencies->format($special['products_price']) . '</span> <span class="specialPrice">' . $this->currencies->format($special['specials_new_products_price']) . '</span>');
        $records[] = array_merge($special, $new_price);
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->specials_model->get_total($params),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function list_manufacturers()
  {
    $this->load->model('manufacturers_model');
    
    $entries = $this->manufacturers_model->get_manufacturers_data();
    
    $records = array(array('manufacturers_id' => '',
                           'manufacturers_name' => lang('top_manufacturers')));
    
    $records = array_merge($records, $entries);
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function list_categories()
  {
    $this->load->library('category_tree');
    
    $records = array(array('id' => 0,
                           'text' => lang('top_category')));
    
    foreach ($this->category_tree->getTree() as $value) {
      $category_id = strval($value['id']);
      $margin = 0;
      
      if (strpos($category_id, '_') !== FALSE)
      {
        $n = count(explode('_', $category_id)) - 1;
        
        $margin = $n * 10;
      }
      
      $records[] = array('id' => $value['id'],
                         'text' => $value['title'], 
                         'margin' => $margin);
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function save_specials()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    $data = array('products_id' => $this->input->post('products_id'), 
                  'specials_price' => $this->input->post('specials_new_products_price'),
                  'start_date' => $this->input->post('start_date'), 
                  'expires_date' => $this->input->post('expires_date'), 
                  'specials_date_added' => $this->input->post('specials_date_added'), 
                  'status' => $this->input->post('status') ? 1 : NULL);
    
    if ($this->specials_model->save($this->input->post('specials_id'), $data))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));   
    }
    
    return $response;
  }
  
  public function list_products()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $this->load->library('tax');
    $this->load->model('products_model');
    
    $tax_classes = $this->products_model->get_tax_classes();
    
    $products = $this->specials_model->get_products($start, $limit);
    
    $records = array();
    if (!empty($products))
    {
      foreach($products as $product)
      {
        if ($product['products_tax_class_id'] > 0)
        {
          foreach($tax_classes as $tax_class)
          {
            if ($tax_class['id'] == $product['products_tax_class_id'])
            {
              $rate = $tax_class['rate'];
            }
            
            break;
          }
        }
        else
        {
          $rate = 0;
        }
        
        $records[] = array('products_id' => $product['products_id'],
                           'products_name' => $product['products_name'],
                           'rate' => $rate);
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->specials_model->get_total_products(),
                 EXT_JSON_READER_ROOT => $records); 
  }
  
  public function delete_special()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    if ($this->specials_model->delete($this->input->post('specials_id')))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));   
    }
    
    return $response;
  }
  
  public function delete_specials()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    $error = FALSE;
    
    $specials_ids = json_decode($this->input->post('batch'));
    
    if (!empty($specials_ids))
    {
      foreach($specials_ids as $id)
      {
        if (!$this->specials_model->delete($id))
        {
          $error = TRUE;
          break;
        }
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
  
  public function load_specials()
  {
    $this->load->helper('date');
    
    $data = $this->specials_model->get_data($this->input->post('specials_id'));
    
    $data['start_date'] = mdate('%Y-%m-%d', human_to_unix($data['start_date']));
    $data['expires_date'] = mdate('%Y-%m-%d', human_to_unix($data['expires_date']));
    
    return array('success' => TRUE, 'data' => $data); 
  }
}

/* End of file specials.php */
/* Location: ./system/modules/specials/controllers/specials.php */