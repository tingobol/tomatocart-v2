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
 * @filesource ./system/modules/orders_status/controllers/orders_status.php
 */

class Orders_Status extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('orders_status_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('orders_status_grid');
    $this->load->view('orders_status_dialog');
  }
  
  public function list_orders_status()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $statuses = $this->orders_status_model->get_orders_status($start, $limit);
    
    $records = array();
    if (!empty($statuses))
    {
      foreach($statuses as $status)
      {
        $orders_status_name = $status['orders_status_name'];
        
        if ($status['orders_status_id'] == DEFAULT_ORDERS_STATUS_ID)
        {
          $orders_status_name .= ' (' . lang('default_entry') . ')';
        }
        
        $records[] = array('orders_status_id' => $status['orders_status_id'],
                           'language_id' => lang_id(),
                           'orders_status_name' => $orders_status_name,
                           'public_flag' => $status['public_flag']);
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->orders_status_model->get_total(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function set_status()
  {
    if ($this->orders_status_model->set_status($this->input->post('orders_status_id'), $this->input->post('flag')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function save_orders_status()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    $data = array('name' => $this->input->post('name'), 
                  'public_flag' => $this->input->post('public_flag') == 'on' ? 1 : 0);
    
    if ($this->orders_status_model->save($this->input->post('orders_status_id'), $data, $this->input->post('default') == 'on' ? TRUE : FALSE))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function load_orders_status()
  {
    $statuses = $this->orders_status_model->load_order_status($this->input->post('orders_status_id'));
    
    $data = array();
    if (DEFAULT_ORDERS_STATUS_ID == $this->input->post('orders_status_id'))
    {
      $data['default'] = '1';
    }
        
    if (!empty($statuses))
    {
      foreach($statuses as $status)
      {
        $data['name[' . $status['language_id'] . ']'] = $status['orders_status_name'];
        $data['public_flag'] = $status['public_flag'];
      }
    }
    
    return array('success' => TRUE, 'data' => $data);
  }
  
  public function delete_orders_status()
  {
    $error = FALSE;
    $feedback = array();
    
    if ($this->input->post('orders_status_id') == DEFAULT_ORDERS_STATUS_ID)
    {
      $error = TRUE;
      
      $feedback[] = lang('delete_error_order_status_prohibited');
    }
    else
    {
      $check_orders = $this->orders_status_model->check_orders($this->input->post('orders_status_id'));
      
      if ($check_orders > 0)
      {
        $error = TRUE;
        $feedback[] = sprintf(lang('delete_error_order_status_in_use'), $check_orders);
      }
      
      $check_history = $this->orders_status_model->check_history($this->input->post('orders_status_id'));
      
      if ($check_history > 0)
      {
        $error = TRUE;
        $feedback[] = sprintf(lang('delete_error_order_status_used'), $check_history);
      }
    }
    
    if ($error === FALSE)
    {
      if ($this->orders_status_model->delete($this->input->post('orders_status_id')))
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
  
  public function batch_delete_orders_status()
  {
    $orders_status_ids = json_decode($this->input->post('batch'));
    
    $error = FALSE;
    $feedback = array();
    
    foreach($orders_status_ids as $id)
    {
      if ($id == DEFAULT_ORDERS_STATUS_ID)
      {
        $error = TRUE;
        $feedback[] = lang('batch_delete_error_order_status_prohibited');
      }
      else
      {
        $check_orders = $this->orders_status_model->check_orders($id);
        
        if ($check_orders > 0) 
        {
          $error = TRUE;
          $feedback[] = lang('batch_delete_error_order_status_in_use');
          break;
        }
        
        $check_history = $this->orders_status_model->check_history($id);
        
        if ($check_history > 0)
        {
          $error = TRUE;
          $feedback[] = lang('batch_delete_error_order_status_used');
          break;
        }
      }
    }
    
    if ($error === FALSE)
    {
      foreach($orders_status_ids as $id)
      {
        if (!$this->orders_status_model->delete($id))
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
}

/* End of file orders_status.php */
/* Location: ./system/modules/orders_status/controllers/orders_status.php */