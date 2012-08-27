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
 * @filesource ./system/modules/feature_products_manager/controllers/feature_products_manager.php
 */

class Feature_Products_Manager extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('feature_products_manager_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('feature_products_manager_grid');
  }
  
  public function list_products()
  {
    $this->load->library('category_tree');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $current_category_id = end(explode( '_', ($this->input->get_post('categories_id') ? $this->input->get_post('categories_id') : 0)));
    
    $in_categories = array();
    if ($current_category_id > 0)
    {
      $this->category_tree->set_breadcrumb_usage(FALSE);
      
      $in_categories = array($current_category_id);
      
      foreach($this->category_tree->getTree($current_category_id) as $category)
      {
        $in_categories[] = $category['id'];
      }
      
      $products = $this->feature_products_manager_model->get_products($start, $limit, $in_categories);
    }
    
    $products = $this->feature_products_manager_model->get_products($start, $limit, $in_categories);
    
    $records = array();
    if (!empty($products))
    {
      foreach($products as $product)
      {
        $records[] = array('products_id'   => $product['products_id'],
                           'products_name' => $product['products_name'],
                           'sort_order'    => $product['sort_order']);
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $records, 
                 EXT_JSON_READER_TOTAL => $this->feature_products_manager_model->get_total($in_categories));
  }
  
  public function get_categories()
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
  
  public function delete_product()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    if ($this->feature_products_manager_model->delete($this->input->post('products_id')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_products()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    $products_ids = json_decode($this->input->post('batch'));
    
    $error = FALSE;
    foreach($products_ids as $id)
    {
      if ($this->feature_products_manager_model->delete($id) === FALSE)
      {
        $error = TRUE;
        break;
      }
    }
    
    if ($error === FALSE) 
    {      
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    } else 
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function update_sort_order()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    if ($this->feature_products_manager_model->save($this->input->post('products_id'), $this->input->post('sort_value')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
}

/* End of file feature_products_manager.php */
/* Location: ./system/modules/feature_products_manager/controllers/feature_products_manager.php */
