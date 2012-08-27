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
 * @filesource modules/weight_classes/controllers/weight_classes.php
 */

class Weight_Classes extends TOC_Controller
{
  public function __construct() 
  {
    parent::__construct();
    
    $this->load->model('weight_classes_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('weight_classes_grid');
    $this->load->view('weight_classes_dialog');
  }
  
  public function list_weight_classes()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $classes = $this->weight_classes_model->get_classes($start, $limit);
    
    $records = array();
    if (!empty($classes))
    {
      foreach($classes as $class)
      {
        $class_name = $class['weight_class_title'];
        
        if ($class['weight_class_id'] == SHIPPING_WEIGHT_UNIT) 
        {
          $class_name .= ' (' . lang('default_entry') . ')';
        }
        
        $records[] = array('weight_class_title' => $class_name,
                          'weight_class_id' => $class['weight_class_id'],
                          'weight_class_key' => $class['weight_class_key']);   
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->weight_classes_model->get_total(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function get_weight_classes_rules()
  {
    $rules = $this->weight_classes_model->get_rules();
    
    return array('success' => TRUE, 'rules' => $rules);
  }
  
  public function save_weight_classes()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    $data = array('name' => $this->input->post('name'),
                  'key' => $this->input->post('key'),
                  'rules' => $this->input->post('rules'));
    
    if ($this->weight_classes_model->save($this->input->post('weight_class_id'), $data, $this->input->post('is_default') == 'on' ? TRUE : FALSE))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed')); 
    }
    
    return $response;
  }
  
  public function load_weight_classes()
  {
    $classes_infos = $this->weight_classes_model->get_infos($this->input->post('weight_class_id'));
    
    $data = array();
    if (!empty($classes_infos))
    {
      foreach($classes_infos as $class_info)
      {
        if ($class_info['language_id'] == lang_id())
        {
          $data = array_merge($data, $class_info);
          
          if ($class_info['weight_class_id'] == SHIPPING_WEIGHT_UNIT)
          {
            $data['is_default'] = 1;
          }
        }
        
        $data['name[' . $class_info['language_id'] . ']'] =  $class_info['weight_class_title'];
        $data['key[' . $class_info['language_id'] . ']'] = $class_info['weight_class_key'];
      }
    }
    
    $rules_infos = $this->weight_classes_model->get_rules_infos($this->input->post('weight_class_id'));
    
    $rules = array();
    if (!empty($rules_infos))
    {
      foreach($rules_infos as $rules_info)
      {
        $rules[] = array('weight_class_id' => $rules_info['weight_class_to_id'],
                         'weight_class_rule' => $rules_info['weight_class_rule'],
                         'weight_class_title' => $rules_info['weight_class_title']);   
      }
    }
    
    $data['rules'] = $rules;
    
    return array('success' => TRUE, 'data' => $data);
  }
  
  public function delete_weight_class()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    $error = false;
    $feedback = array();
    
    if ($this->input->post('weight_classes_id') == SHIPPING_WEIGHT_UNIT)
    {
      $error = true;
      $feedback[] = lang('delete_error_weight_class_prohibited');
    }
    else
    {
      $check_products = $this->weight_classes_model->get_products($this->input->post('weight_classes_id'));
      
      if ($check_products > 0)
      {
        $error = TRUE;
        $feedback[] = sprintf(lang('delete_error_weight_class_in_use'), $check_products);
      }
    }
    
    if ($error === FALSE)
    {
      if ($this->weight_classes_model->delete($this->input->post('weight_classes_id')))
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
  
  public function delete_weight_classes()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    $error = FALSE;
    $feedback = array();
    
    $weight_classes_ids = json_decode($this->input->post('batch'));
    
    foreach($weight_classes_ids as $id)
    {
      if ( $id == SHIPPING_WEIGHT_UNIT ) {
        $error = TRUE;
        $feedback[] = lang('delete_error_weight_class_prohibited');
      }
      else
      {
        $check_products = $this->weight_classes_model->get_products($id);
        
        if ($check_products > 0)
        {
          $error = TRUE;
          $feedback[] = lang('batch_delete_error_weight_class_in_use');
          break;
        }
      }
    }
    
    if ($error === FALSE)
    {
      foreach($weight_classes_ids as $id)
      {
        if ($this->weight_classes_model->delete($id) === FALSE)
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


/* End of file weight_classes.php */
/* Location: ./system/modules/weight_classes/controllers/weight_classes.php */