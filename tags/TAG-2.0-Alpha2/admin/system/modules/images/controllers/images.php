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
 * @filesource ./system/modules/images/controllers/images.php
 */

class Images extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('images_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('images_grid');
    $this->load->view('images_check_dialog');
    $this->load->view('images_resize_dialog');
  }
  
  public function list_images()
  { 
    $records = array(
      array('module' => lang('images_check_title'), 'run' => 'checkimages'), 
      array('module' => lang('images_resize_title'), 'run' => 'resizeimages')
    );
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function check_images()
  {
    $counter = array();
    
    $products_images = $this->images_model->get_products_images();
    
    $images_groups = $this->get_groups();
    
    if (!empty($products_images))
    {
      foreach($products_images as $image)
      {
        foreach($images_groups as $group)
        {
          if (!isset($counter[$group['id']]['records']))
          {
            $counter[$group['id']]['records'] = 0;
          }
          
          if (!isset($counter[$group['id']]['existing']))
          {
            $counter[$group['id']]['existing'] = 0;
          }
        
          $counter[$group['id']]['records']++;
          
          if (file_exists(ROOTPATH . '/images/products/' . $group['code'] . '/' . $image['image']))
          {
            $counter[$group['id']]['existing']++;
          }
        }
      }
    }
    
    $records = array();
    foreach($counter as $group_id => $value)
    {
      $records[] = array('group' => $images_groups[$group_id]['title'], 
                         'count' => $value['existing'] . ' / ' . $value['records']);
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function get_image_groups()
  {
    $images_groups = $this->get_groups();
    
    $groups = array();
    foreach($images_groups as $group)
    {
      if ($group['id'] != '1')
      {
        $groups[] = array('text' => $group['title'],
                          'id' => $group['id']);
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $groups);
  }
  
  public function list_images_resize_result()
  {
    ini_set('max_execution_time', 1800);
    
    $overwrite = FALSE;
    
    if ($this->input->get_post('overwrite') == '1')
    {
      $overwrite = TRUE;
    }
    
    $groups = json_decode($this->input->get_post('groups'));
    
    if (!is_array($groups))
    {
      return FALSE;
    }
    
    $images = $this->images_model->get_products_images();
    $images_groups = $this->get_groups();
    
    $counter = array();
    foreach($images as $image)
    {
      foreach($images_groups as $group)
      {
        if ($group['id'] != 1 && in_array($group['id'], $groups))
        {
          if (!isset($counter[$group['id']]))
          {
            $counter[$group['id']] = 0;
          }
          
          if ($overwrite === TRUE || !file_exists(ROOTPATH . 'images/products/' . $group['code'] . '/' . $image['image']))
          {
            $this->admin_image->resize($image['image'], $group['id']);
            
            $counter[$group['id']]++;
          }
        }
      }
    }
    
    $records = array();
    foreach($counter as $group_id => $value)
    {
      $records[] = array('group' => $images_groups[$group_id]['title'], 'count' => $value);
    }
    
    return array('success' => TRUE, EXT_JSON_READER_ROOT => $records);
  }
  
  private function get_groups()
  {
    $this->load->library(array('image', 'admin_image'));
    
    return $this->admin_image->getGroups();
  }
}

/* End of file images.php */
/* Location: ./system/modules/images/controllers/images.php */
