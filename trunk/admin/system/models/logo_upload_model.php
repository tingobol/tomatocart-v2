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
 * @filesource ./system/modules/logo_upload/models/logo_upload_model.php
 */

class Logo_Upload_Model extends CI_Model
{
  private $logo_path;
  
  public function __construct()
  {
    parent::__construct();
    
    $this->logo_path = ROOTPATH . 'images/';
    
    $this->upload->initialize(array('upload_path' => $this->logo_path, 'allowed_types' => 'gif|jpg|png'));
  }
  
  public function upload($field)
  {
    if ($this->upload->do_upload($field))
    {
      $data = $this->upload->data();
      
      $this->delete_logo('originals');
      
      $original = $this->logo_path . 'logo_originals.' . $data['image_type'];
      
      copy($data['full_path'], $original);
      @unlink($data['full_path']);
      
      $templates_map = directory_map(ROOTPATH . 'templates', 2);
  
      foreach($templates_map as $template => $files)
      {
        if (file_exists(ROOTPATH . 'templates/' . $template . '/template.xml'))
        {
          $this->delete_logo($template);
          
          $xml_info = simplexml_load_file(ROOTPATH . 'templates/' . $template . '/template.xml');
          
          $logo_info = $xml_info->Logo[0]->attributes();
          
          $logo_height = $logo_info['height'];
          $logo_width = $logo_info['width'];
          
          $dest_image = $this->logo_path . 'logo_' . $template . '.' . $data['image_type'];
          
          $this->image_lib->initialize(array('image_library' => 'gd2', 
                                             'source_image' => $original,
                                             'new_image' => $dest_image,
                                             'create_thumb' => TRUE, 
                                             'maintain_ratio' => TRUE, 
                                             'width' => 22, 
                                             'height' => 22));
          
          $this->image_lib->resize();
        }
      }
      
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_original_logo()
  {
    $images_map = directory_map($this->logo_path);
    
    if (!empty($images_map))
    {
      foreach($images_map as $image)
      {
        if (!is_array($image))
        {
          $filename = explode(".", $image);
          
          if ($filename[0] == 'logo_originals')
          {
            return 'logo_originals.' . $filename[1];
          }
        }
      }
    }
    
    return FALSE;
  }
  
  private function delete_logo($code)
  {
    $logo = 'logo_' . $code;
    
    $images_map = directory_map($this->logo_path);
    
    if (!empty($images_map))
    {
      foreach($images_map as $image)
      {
        if (!is_array($image))
        {
          $filename = explode(".", $image);
          
          if ($filename[0] == $logo)
          {
            @unlink($this->logo_path . $image);
          }
        }
      }
    }
  }
}


/* End of file logo_upload_model.php */
/* Location: ./system/modules/logo_upload/models/logo_upload_model.php */