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
 * @filesource ./system/modules/product_variants/controllers/product_variants.php
 */

class Product_Variants extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('product_variants_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('product_variants_main_panel');
    $this->load->view('product_variants_groups_grid');
    $this->load->view('product_variants_entries_grid');
    $this->load->view('product_variants_groups_dialog');
    $this->load->view('product_variants_entries_dialog');
  }
  
  public function list_product_variants()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $groups = $this->product_variants_model->get_variants_groups($start, $limit);
    
    $records = array();
    if (!empty($groups))
    {
      foreach($groups as $group)
      {
        $entries = $this->product_variants_model->get_total_entries($group['products_variants_groups_id']);
        
        $records[] = array( 'products_variants_groups_id' => $group['products_variants_groups_id'],
                            'products_variants_groups_name' => $group['products_variants_groups_name'],
                            'total_entries' => $entries);
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->product_variants_model->get_total(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function list_product_variants_entries()
  {
    $entries = $this->product_variants_model->get_variants_entries($this->input->get_post('products_variants_groups_id'));
    
    return array(EXT_JSON_READER_ROOT => $entries);
  }
  
  public function delete_product_variants_entry()
  {
    $error = FALSE;
    $feedback = array();
    
    $entry_data = $this->product_variants_model->get_entry_data($this->input->post('products_variants_values_id'));
    if ($entry_data['total_products'] > 0)
    {
      $error = TRUE;
      $feedback[] = sprintf(lang('delete_error_group_entry_in_use'), $entry_data['total_products']);
    }
    
    if ($error === FALSE)
    {
      if ($this->product_variants_model->delete_entry($this->input->post('products_variants_values_id'), $this->input->post('products_variants_groups_id')))
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
  
  public function delete_product_variants_entries()
  {
    $values_ids = json_decode($this->input->post('batch'));
    
    $error = FALSE;
    $feedback = array();
    $check_products_array = array();
    
    foreach($values_ids as $id)
    {
      $entry_data = $this->product_variants_model->get_entry_data($id);
      
      if ($entry_data['total_products'] > 0)
      {
        $check_products_array[] = $entry_data['products_variants_values_name'];
      }
    }
    
    if (!empty($check_products_array)) 
    {
      $error = TRUE;
      $feedback[] = lang('batch_delete_error_group_entries_in_use') . '<p>' . implode(', ', $check_products_array) . '</p>';
    }
    
    
    if ($error === FALSE)
    {
      foreach($values_ids as $id)
      {
        if (!$this->product_variants_model->delete_entry($id, $this->input->post('products_variants_groups_id')))
        {
          $error = TRUE;
          break;
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
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
    }
    
    return $response;
  }
  
  public function save_product_variants_entry()
  {
    $data = array('name' => $this->input->post('products_variants_values_name'), 
                  'products_variants_groups_id' => $this->input->post('products_variants_groups_id'));
    
    if ($this->product_variants_model->save_entry($this->input->post('products_variants_values_id'), $data))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));   
    }
    
    return $response;
  }
  
  public function load_product_variants_entry()
  {
    $entries_data = $this->product_variants_model->get_entries_data($this->input->post('products_variants_values_id'));
    
    $result = array();
    if (!empty($entries_data))
    {
      foreach($entries_data as $data)
      {
        if ($data['language_id'] == lang_id())
        {
          $result['products_variants_values_id'] = $data['products_variants_values_id'];
        }
        
        $result['products_variants_values_name[' . $data['language_id'] . ']'] = $data['products_variants_values_name'];
      }
    }
    
    return array('success' => TRUE, 'data' => $result); 
  }
  
  public function save_product_variant()
  {
    $data = array('name' => $this->input->post('products_variants_groups_name'));
    
    if ($this->product_variants_model->save($this->input->post('products_variants_groups_id'), $data))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function load_product_variant()
  {
    $groups_data = $this->product_variants_model->get_groups_data($this->input->post('products_variants_groups_id'));
    
    $result = array();
    foreach($groups_data as $data)
    {
      if ($data['language_id'] == lang_id())
      {
        $result['products_variants_groups_id'] = $data['products_variants_groups_id'];
      }
      
      $result['products_variants_groups_name[' . $data['language_id'] . ']'] = $data['products_variants_groups_name'];
    }
    
    return array('success' => TRUE, 'data' => $result); 
  }
  
  public function delete_product_variant()
  {
    $error = FALSE;
    $feedback = array();
    
    $total_products = $this->product_variants_model->get_group_products($this->input->post('products_variants_groups_id'));
    
    if ($total_products > 0)
    {
      $error = TRUE;
      $feedback[] = sprintf(lang('delete_error_variant_group_in_use'), $total_products);
    }
    
    if ($error === FALSE)
    {
      if ($this->product_variants_model->delete($this->input->post('products_variants_groups_id')))
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

/* End of file product_variants.php */
/* Location: ./system/modules/product_variants/controllers/product_variants.php */