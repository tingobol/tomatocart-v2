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
 * @filesource
 */

class Slide_Images extends TOC_Controller 
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('slide_images_model');
    $this->load->driver('cache', array('adapter' => 'file'));
  }
  
  public function get_image_groups_filter() {
    $groups = $this->slide_images_model->get_groups();
    
    $result = array(array('id' => '', 'text' => '--All--'));
    if ($groups !== FALSE) {
      foreach ($groups as $group) {
        $result[] = array('id' => $group, 'text' => $group);
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $result);
  }
  
  public function get_image_groups() {
    $groups = $this->slide_images_model->get_groups();
    
    $result = array();
    if ($groups !== FALSE) {
      foreach ($groups as $group) {
        $result[] = array('id' => $group, 'text' => $group);
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $result);
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('slide_images_grid');
    $this->load->view('slide_images_dialog');
  }
  
  public function list_slide_images()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    $group = $this->input->get_post('group') ? $this->input->get_post('group') : '';
    
    $images = $this->slide_images_model->get_images($start, $limit, $group);
    
    $records = array();
    if (!empty($images))
    {
      foreach($images as $image)
      {
        $slide_image = '';
        if (file_exists(ROOTPATH . 'images/' . $image['image']))
        {
          list($orig_width, $orig_height) = getimagesize(ROOTPATH . 'images/' . $image['image']);
          $width = intval($orig_width * 60 / $orig_height);
          
          $slide_image = '<img src="' . IMGHTTPPATH . $image['image'] . '" width="' . $width . '" height="80" />';
        }
        
        $records[] = array('image_id' => $image['image_id'],
                           'image' =>  $slide_image,
                           'image_url' => $image['image_url'],
                           'sort_order' => $image['sort_order'],
                           'group' => $image['group'],
                           'status' => $image['status']);
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->slide_images_model->get_totals(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function save_slide_images()
  {
    $this->load->library('upload');
    
    $new_image_group = $this->input->post('new_image_group');
    $group = empty($new_image_group) ? $this->input->post('group') : $new_image_group;
    
    $data = array('status' => $this->input->post('status'),
                  'image_url' => $this->input->post('image_url'),
                  'description' => $this->input->post('description'),
                  'group' => $group,
                  'sort_order' => $this->input->post('sort_order'));
    
    $image_id = $this->input->post('image_id');
    
    $error = FALSE;
    $feedback = array();
    
    if (empty($image_id))
    {
      foreach(lang_get_all() as $l)
      {
        if (empty($_FILES['image' . $l['id']]['name']))
        {
          $error = TRUE;
          $feedback [] = sprintf(lang('ms_error_image_empty'), $l['name']);
          
          break;
        }
      }
    }
    
    if ($error === FALSE)
    {
      if ($this->slide_images_model->save($image_id, $data))
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
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br>' . implode('<br>', $feedback));
    }
    
    header('Content-Type: text/html');
    
    return $response;
  }
  
  public function delete_slide_image()
  {
    if ($this->slide_images_model->delete($this->input->post('image_id')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function batch_delete()
  {
    $error = FALSE;
    
    $batch = $this->input->post('batch');
    
    $images_ids = json_decode($batch);
    
    if (!empty($images_ids))
    {
      foreach($images_ids as $id)
      {
        if ($this->slide_images_model->delete($id) == FALSE)
        {
          $error = TRUE;
          break;
        }
      }
    }
    else
    {
      $error = TRUE;
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
  
  public function set_status()
  {
    if ($this->slide_images_model->set_status($this->input->post('image_id'), $this->input->post('flag')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));  
    }
    
    return $response;
  }
  
  public function load_slide_images()
  {
    $data = $this->slide_images_model->get_data($this->input->post('image_id'));
    
    return array('success' => true, 'data' => $data);
  }
}

/* End of file slide_images.php */
/* Location: ./system/modules/slide_images/controllers/slide_images.php */