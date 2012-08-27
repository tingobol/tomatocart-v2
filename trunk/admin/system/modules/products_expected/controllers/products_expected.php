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
 * @filesource .system/modules/products_expected/controllers/products_expected.php
 */

class Products_Expected extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('products_expected_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('products_expected_grid');
    $this->load->view('products_expected_dialog');
  }
  
  public function list_products_expected()
  {
    $this->load->helper('date');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $products = $this->products_expected_model->get_products($start, $limit);
    
    $records = array();
    foreach($products as $product)
    {
       $records[] = array('products_id' => $product['products_id'],
                         'products_name' => $product['products_name'],
                         'products_date_available' =>  mdate('%Y/%m/%d', human_to_unix($product['products_date_available'])));         
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->products_expected_model->get_total(),
                 EXT_JSON_READER_ROOT => $records); 
  }
  
  public function save_products_expected()
  {
    $data = array('date_available' => $this->input->post('products_date_available'));
    
    if ($this->products_expected_model->save_date_available($this->input->post('products_id'), $data))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed')); 
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function load_products_expected()
  {
    $this->load->helper('date');
    $this->load->model('products_model');
    
    $data = $this->products_expected_model->get_data($this->input->post('products_id'));
    
    $data['products_date_available'] = mdate('%Y-%m-%d', human_to_unix($data['products_date_available']));
    
    return array( 'success' => TRUE, 'data' => $data );
  }
}

/* End of file products_expected.php */
/* Location: ./system/modules/products_expected/controllers/products_expected.php */
