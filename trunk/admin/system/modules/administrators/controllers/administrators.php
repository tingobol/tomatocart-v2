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
 * @filesource ./system/modules/administrators/controllers/administrators.php
 */

class Administrators extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('administrators_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('administrators_grid');
    $this->load->view('administrators_dialog');
  }
  
  public function list_administrators()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $admins = $this->administrators_model->get_administrators($start, $limit);
    
    $response = array(EXT_JSON_READER_TOTAL => $this->administrators_model->get_total(),
                      EXT_JSON_READER_ROOT => $admins);
                      
    return $response;
  }
  
  public function get_accesses()
  {
    if ($this->input->get_post('aID') > 0)
    {
      $modules = $this->load_access_modules($this->input->get_post('aID'));  
    
    }
    else
    {
      $global = $this->input->get_post('global') == 'on' ? TRUE : FALSE;
    
      $modules = $this->get_modules($global);
    }
    
    return $modules;
  }
  
  public function save_administrator()
  {
    $this->load->library('access');
    $this->load->library('encrypt');
    
    $this->load->helper('email');
    
    $data = array('username' => $this->input->post('user_name'), 
                  'password' => $this->input->post('password'), 
                  'email_address' => $this->input->post('email_address'));
    
    $modules = json_decode($this->input->post('modules'));
    
    if ($this->input->post('access_globaladmin') === 'on')
    {
      $modules = array('*');
    }
    
    $admin_id = $this->input->post('aID');
    $admin_data = $this->session->userdata('admin_data');
    switch ($this->administrators_model->save($admin_id, $data, $modules))
    {
      case 1:
        if (is_numeric($admin_id) && $admin_id == $admin_data['id'])
        {
          $admin_data['access'] = $this->access->get_user_levels($admin_id);
          
          $this->session->set_userdata($admin_data);
        }
        
        $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        break;
        
      case -1:
        $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        break;
        
      case -2:
        $response = array('success' => FALSE, 'feedback' => lang('ms_error_username_already_exists'));
        break;
      
      case -3:
        $response = array('success' => FALSE, 'feedback' => lang('ms_error_email_format'));
        break;
        
      case -4:
        $response = array('success' => FALSE, 'feedback' => lang('ms_error_email_already_exists'));
        break;
    }
    
    return $response;
  }
  
  public function delete_administrator()
  {
    if ($this->administrators_model->delete($this->input->post('adminId')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_administrators()
  {
    $admin_ids = json_decode($this->input->post('batch'));
    
    $error = FALSE;
    
    foreach($admin_ids as $id)
    {
      if (!$this->administrators_model->delete($id))
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
  
  public function load_administrator()
  {
    $data = $this->administrators_model->get_data($this->input->post('aID'));
    
    $response = array('success' => TRUE, 'data' => $data);
    
    return $response;
  }
  
  public function load_access_modules($administrators_id)
  {
    $modules = $this->administrators_model->get_modules($administrators_id);
    
    $global_access = FALSE;
    if (!empty($modules))
    {
      foreach($modules as $module)
      {
        if ($module['module'] == '*')
        {
          $global_access = TRUE;
          break;
        }
      }
    }
    
    $access_modules = array();
    if ($global_access == TRUE)
    {
      $access_modules = $this->get_modules(TRUE);
    }
    else
    {
      foreach($modules as $module)
      {
        $access_modules[] = strtolower(str_replace(' ', '_', $module['module']));
      }
      
      $access_modules = $this->get_modules(FALSE, $access_modules);
    }
    
    return $access_modules;
  }
  
  public function check_email($email = null)
  {
    if ($this->administrators_model->check_email($email))
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function generate_password($email)
  {
    $this->load->helper('string');
    $this->load->helper('core');
    $this->load->library('encrypt');
    $this->load->module('email_templates');
    
    $password = trim(random_string('alnum', 8));
    
    if ($this->administrators_model->update_password($email, $password))
    {
    
      $admin = $this->administrators_model->get_admin($email);
      
      if (!empty($admin))
      {
        if (class_exists('Email_Templates'))
        {
          Email_Templates::get_email_template('admin_password_forgotten');
          
          if (is_subclass_of($this->admin_password_forgotten, 'Email_Templates'))
          {
            $this->admin_password_forgotten->set_data($admin['user_name'], get_ip_address(), $password, $email);
            $this->admin_password_forgotten->build_message();
            
            if ($this->admin_password_forgotten->send_email())
            {
              return TRUE;
            }
          }
        }
      }
    }
    
    return FALSE;
  }
  
  private function get_modules($global = FALSE, $modules = array())
  {
    $this->load->helper('directory');
    
    $access_DirectoryListing = directory_map(APPPATH . 'modules/access', 1);
    
    $access_modules = array();
    foreach($access_DirectoryListing as $file)
    {
      $module = substr($file, 0, strrpos($file, '.'));
      
      $module_class = 'TOC_Access_' . ucfirst($module);
      
      if ( !class_exists( $module_class ) ) {
        require_once(APPPATH . 'modules/access/' . $module . '.php');
      }
      
      $module_obj = new $module_class();
      
      if (is_object($module_obj))
      {
        $title = $module_obj->get_group_title($module_obj->get_group());
        
        $access_modules[$title][] = array('id' => $module_obj->get_module(),
                                          'text' => $module_obj->get_title(),
                                          'leaf' => TRUE, 
                                          'checked' => ($global == TRUE || in_array($module_obj->get_module(), $modules)) ? TRUE : FALSE);
      }
    }
    
    ksort($access_modules);
    
    $access_options = array(); 
    $count = 1;
    foreach ( $access_modules as $group => $modules ) {
      $access_option['id'] = $count;
      $access_option['text'] = $group;
      
      $childrens = array();
      foreach($modules as $module) {
        $childrens[] = $module;
      }

      $access_option['children'] = $childrens;
      
      $access_options[] = $access_option;
      $count++;
    }
    
    return $access_options;
  }
}

/* End of file administrators.php */
/* Location: ./system/modules/administrators/controllers/administrators.php */