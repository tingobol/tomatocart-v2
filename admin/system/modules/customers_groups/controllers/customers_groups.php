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
 * @filesource .system/modules/customers_groups/controllers/customers_groups.php
 */

class Customers_Groups extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('customers_groups_model');
  }

  public function show()
  {
    $this->load->view('main');
    $this->load->view('customers_groups_grid');
    $this->load->view('customers_groups_dialog');
  }
  
  public function list_customers_groups()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $groups = $this->customers_groups_model->get_groups($start, $limit);
    
    $records = array();
    if (!empty($groups))
    {
      foreach($groups as $group)
      {
        $group_name = $group['customers_groups_name'];
        if ($group['is_default'])
        {
          $group_name .= '(' . lang('default_entry') . ')';
        }
        
        $records[] = array(
          'language_id' => $group['language_id'],
          'customers_groups_id' => $group['customers_groups_id'],
          'customers_groups_name' => $group_name,
          'customers_groups_discount' => sprintf("%d%%", $group['customers_groups_discount'])
        );     
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->customers_groups_model->get_total(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function save_customers_groups()
  {
    $data = array('customers_groups_id' => $this->input->post('groups_id'),
                  'customers_groups_discount' => $this->input->post('customers_groups_discount'),
                  'customers_groups_name' => $this->input->post('customers_groups_name'),
                  'is_default' => ($this->input->post('is_default') ? $this->input->post('is_default') : 0));
    
    if ($this->customers_groups_model->save($this->input->post('groups_id'), $data))
    {
      $response = array('success' => TRUE , 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE , 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_customers_group()
  {
    $error = FALSE;
    $feedback = array();
    
    $data = $this->customers_groups_model->get_data($this->input->post('customer_groups_id'));
    
    if ($data['is_default'] == 1)
    {
      $error = TRUE;
      $feedback[] = lang('delete_error_customer_group_prohibited');
    }
    
    $check_in_use = $this->customers_groups_model->get_in_use($this->input->post('customer_groups_id'));
    if ($check_in_use > 0)
    {
      $error = TRUE;
      $feedback[] = sprintf(lang('delete_error_customer_group_in_use'), $check_in_use);
    }
    
    if ($error === FALSE)
    {
      if ($this->customers_groups_model->delete($this->input->post('customer_groups_id')) === FALSE)
      {
        $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
      }
      else
      {
        $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
      }
    }
    else
    {
      $feedback = implode('<br />', $feedback);
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . $feedback);
    }
    
    return $response;
  }
  
  public function delete_customers_groups()
  {
    $error = FALSE;
    $feedback = array();
    
    $customers_groups_ids = json_decode($this->input->post('batch'));
    
    foreach($customers_groups_ids as $id)
    {
      $data = $this->customers_groups_model->get_data($id);
      
      if ($data['is_default'] == 1)
      {
        $error = TRUE;
        $feedback[] = lang('delete_error_customer_group_prohibited');
        break;
      }
      
      $check_in_use = $this->customers_groups_model->get_in_use($id);
      
      if ($check_in_use > 0)
      {
        $error = TRUE;
        $check_customers_flag[] = $data['customers_groups_name'];
        break;
      }
    }
    
    if ($error === FALSE)
    {
      foreach($customers_groups_ids as $id)
      {
        if ($this->customers_groups_model->delete($id) === FALSE)
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
      if (!empty($check_customers_flag))
      {
        $feedback[] = lang('batch_delete_error_customer_group_in_use') . '<br />' . implode(', ', $check_customers_flag);
      }
      
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
    }
    
    return $response;
  }
  
  public function load_customers_groups()
  {
    $infos = $this->customers_groups_model->get_info($this->input->post('groups_id'));
    
    $data = array();
    if (!empty($infos))
    {
      foreach($infos as $info)
      {
        if ($info['language_id'] == lang_id())
        {
          $data['customers_groups_discount'] = $info['customers_groups_discount'];
          $data['is_default'] = $info['is_default'];
        }
        
        $data['customers_groups_name[' . $info['language_id'] . ']'] = $info['customers_groups_name'];
      }
    }
    
    return array('success' => TRUE, 'data' => $data);
  }
}


/* End of file customers_groups.php */
/* Location: .system/modules/customers_groups/controllers/customers_groups.php */