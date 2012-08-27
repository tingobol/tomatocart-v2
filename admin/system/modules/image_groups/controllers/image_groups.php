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

class Image_Groups extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('image_groups_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('image_groups_grid');
    $this->load->view('image_groups_dialog');
  }
  
  public function list_image_groups()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $image_groups = $this->image_groups_model->get_image_groups($start, $limit);
    
    $records = array();
    if (!empty($image_groups))
    {
      foreach($image_groups as $image_group)
      {
        $title = $image_group['title'];
        
        if ($image_group['id'] === DEFAULT_IMAGE_GROUP_ID)
        {
          $title .= ' (' . lang('default_entry') . ')';
        }
        
        $records[] = array('id' => $image_group['id'], 'title' => $title);
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->image_groups_model->get_total(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function save_image_group()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    $data = array('title' => $this->input->post('title'), 
                  'code' => $this->input->post('code'), 
                  'size_width' => $this->input->post('size_width'), 
                  'size_height' => $this->input->post('size_height'), 
                  'force_size' => $this->input->post('force_size') == 'on' ? TRUE : FALSE);
    
    if ($this->image_groups_model->save($this->input->post('image_groups_id'), $data, $this->input->post('is_default') == 'on' ? TRUE : FALSE))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_image_group()
  {
    $error = FALSE;
    $feedback = array();
    
    if ($this->input->post('image_groups_id') == DEFAULT_IMAGE_GROUP_ID)
    {
      $error = TRUE;
      $feedback[] = lang('delete_error_image_group_prohibited');
    }
    
    if ($error == FALSE)
    {
      if ($this->image_groups_model->delete($this->input->post('image_groups_id')))
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
  
  public function delete_image_groups()
  {
    $error = FALSE;
    $feedback = array();
      
    $image_groups_ids = json_decode($this->input->post('batch'));
    foreach($image_groups_ids as $id)
    {
      if ($id == DEFAULT_IMAGE_GROUP_ID)
      {
        $error = TRUE;
        $feedback[] = lang('batch_delete_error_image_group_prohibited');
        
        break;
      }
    }
    
    if ($error === FALSE)
    {
      foreach($image_groups_ids as $id)
      {
        if ($this->image_groups_model->delete($id) === FALSE)
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
  
  public function load_image_group()
  {
    $image_groups = $this->image_groups_model->get_data($this->input->post('image_groups_id'));
    
    $data = array();
    if (!empty($image_groups))
    {
      foreach($image_groups as $image_group)
      {
        $data['title[' . $image_group['language_id'] . ']'] = $image_group['title'];
        if ($image_group['language_id'] == lang_id())
        {
          $data['code'] = $image_group['code'];
          $data['size_width'] = $image_group['size_width'];
          $data['size_height'] = $image_group['size_height'];
          $data['force_size'] = $image_group['force_size'];
        }
      }
    }
    
    if ($this->input->post('image_groups_id') == DEFAULT_IMAGE_GROUP_ID)
    {
      $data['is_default'] = '1';
    }
    
    return array('success' => TRUE, 'data' => $data);
  }
}

/* End of file image_groups.php */
/* Location: ./system/modules/image_groups/controllers/image_groups.php */