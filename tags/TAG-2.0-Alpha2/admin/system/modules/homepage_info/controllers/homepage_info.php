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
 * @filesource ./system/modules/homepage_info/controllers/homepage_info.php
 */

class Homepage_Info extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('homepage_info_model');
    $this->load->driver('cache', array('adapter' => 'file'));
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('homepage_info_dialog');
    $this->load->view('meta_info_panel');
    $this->load->view('homepage_info_panel');
  }
  
  public function save_info()
  {
    $data = array('page_title' => $this->input->post('HOME_PAGE_TITLE'), 
                  'keywords' => $this->input->post('HOME_META_KEYWORD'), 
                  'descriptions' => $this->input->post('HOME_META_DESCRIPTION'), 
                  'index_text' => $this->input->post('index_text'));
    
    if ($this->homepage_info_model->save_data($data))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function load_info()
  {
    
    $data = $this->homepage_info_model->get_data();
    
    return array('success' => TRUE, 'data' => $data);
  }
}

/* End of file homepage_info.php */
/* Location: ./system/modules/homepage_info/controllers/homepage_info.php */