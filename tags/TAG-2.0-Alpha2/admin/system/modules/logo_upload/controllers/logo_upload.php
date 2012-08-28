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
 * @filesource ./system/modules/logo_upload/controllers/logo_upload.php
 */

class Logo_Upload extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->library('upload');
    $this->load->model('logo_upload_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('logo_upload_dialog');
  }
  
  public function save_logo()
  {
    $this->load->helper('directory');
    $this->load->library('image_lib');
    
    if ($this->logo_upload_model->upload('logo_image'))
    {
      $image = $this->logo_upload_model->get_original_logo();
      
      list($orig_width, $orig_height) = getimagesize(ROOTPATH . 'images/' . $image);
      $width = intval(120 * $orig_width / $orig_height);
      
      $response = array('success' => TRUE, 'image' => IMGHTTPPATH . $image, 'height' => 120, 'width' => $width, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed')); 
    }
    
    $this->output->set_header("Content-Type: text/html")->set_output(json_encode($response));
    
    return NULL;
  }
  
  public function get_logo()
  {
    $image = $this->logo_upload_model->get_original_logo();
    
    if (!empty($image))
    {
      list($orig_width, $orig_height) = getimagesize(ROOTPATH . 'images/' . $image);
      $width = intval(120 * $orig_width / $orig_height);
      
      $image = '<img src="' . IMGHTTPPATH . $image . '" width="' . $width . '" height="120" style="padding: 10px" />';

      $response = array('success' => TRUE, 'image' => $image);
    }
    else
    {
      $response = array('success' => FALSE);   
    }
    
    return $response;
  }
}

/* End of file logo_upload.php */
/* Location: ./system/modules/logo_upload/controllers/logo_upload.php */