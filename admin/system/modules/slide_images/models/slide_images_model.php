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

class Slide_Images_Model extends CI_Model 
{
  public function get_groups() {
    $result = $this->db->distinct()->select('group')->from('slide_images')->get();
    
    $groups = FALSE;
    if ($result->num_rows() > 0)
    {
      foreach ($result->result_array() as $row)
      {
        if (!empty($row['group'])) {
          $groups[] = $row['group'];
        }
      }
    }
    
    return $groups;
  }
  
  
  public function get_images($start, $limit, $group = '')
  {
    $this->db
    ->select('*')
    ->from('slide_images')
    ->where('language_id', lang_id());
    
    if (!empty($group)) {
      $this->db->where('group', $group);
    }
    
    $Qimages = $this->db->order_by('sort_order')
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qimages->result_array();
  }
  
  public function save($id = NULL, $data)
  {
    $error = FALSE;
    
    $this->db->trans_begin();
    
    $config['upload_path'] = ROOTPATH . 'images/';
    $config['allowed_types'] = 'gif|jpg|png';
    $this->upload->initialize($config);
      
    if (is_numeric($id))
    {
      foreach(lang_get_all() as $l)
      {
        $image_data = array('description' => $data['description'][$l['id']], 
                            'image_url' => $data['image_url'][$l['id']], 
                            'sort_order' => $data['sort_order'], 
                            'group' => $data['group'], 
                            'status' => $data['status']);
        
        if ($this->upload->do_upload('image' . $l['id']))
        {
          $uploaded_image_info = $this->upload->data();
          
          $image_data['image'] = $uploaded_image_info['file_name'];
          
          $Qcheck = $this->db
          ->select('image')
          ->from('slide_images')
          ->where(array('image_id' => $id, 'language_id' => $l['id']))
          ->get();
          
          $image = $Qcheck->row_array();
          $Qcheck->free_result();
          
          if (!empty($image))
          {
            @unlink($image_path . $image['image']);
          }
        }
        
        $this->db->update('slide_images', $image_data, array('language_id' => $l['id'], 'image_id' => $id));
        
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
          break;
        }
      }
    }
    else
    {
      $Qmaximage = $this->db->select_max('image_id')->get('slide_images');
      
      $maximage = $Qmaximage->row_array();
      $image_id = $maximage['image_id'] + 1;
      
      foreach(lang_get_all() as $l)
      {
        if ($this->upload->do_upload('image' . $l['id']))
        {
          $uploaded_image_info = $this->upload->data();
          
          $this->db->insert('slide_images', array('image_id' => $image_id, 
                                                  'language_id' => $l['id'], 
                                                  'description' => $data['description'][$l['id']], 
                                                  'image' => $uploaded_image_info['file_name'], 
                                                  'image_url' => $data['image_url'][$l['id']], 
                                                  'sort_order' => $data['sort_order'], 'status' => $data['status']));
          
          if ($this->db->trans_status() === FALSE)
          {
            $error = TRUE;
            break;
          }
        }
      }
    }
    
    if ($error === TRUE)
    {
      $this->db->trans_rollback();
      
      return FALSE;
    }
    
    $this->db->trans_commit();
    
    if ($this->cache->get('slide-images') !== FALSE)
    {
      $this->cache->delete('slide-images');
    }
    
    return TRUE;
  }
  
  public function delete($id = NULL)
  {
    $Qimage = $this->db->get_where('slide_images', array('image_id' => $id));
    
    $image = $Qimage->row_array();
    if (!empty($image))
    {
      @unlink(ROOTPATH . 'images/' . $image['image']);
    }
    
    $Qimage->free_result();
    
    $this->db->delete('slide_images', array('image_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      if ($this->cache->get('slide-images') !== FALSE)
      {
        $this->cache->delete('slide-images');
      }
      
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function set_status($id, $flag)
  {
    $this->db->update('slide_images', array('status' => $flag), array('image_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      if ($this->cache->get('slide-images') !== FALSE)
      {
        $this->cache->delete('slide-images');
      }
      
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_data($id)
  {
    $Qimage = $this->db
    ->select('status, sort_order, group')
    ->from('slide_images')
    ->where(array('image_id' => $id, 'language_id' => lang_id()))
    ->get();
    
    $data = $Qimage->row_array();
    
    $Qimage->free_result();
    
    $Qimage_info= $this->db->get_where('slide_images', array('image_id' => $id));
    
    $images_info= $Qimage_info->result_array();
    
    $Qimage_info->free_result();
    
    if (!empty($images_info))
    {
      foreach($images_info as $image_info)
      {
        list($orig_width, $orig_height) = getimagesize(ROOTPATH . 'images/' . $image_info['image']);
        $width = intval($orig_width * 80 / $orig_height);
        
        $slide_image = '<img src="' . IMGHTTPPATH . $image_info['image'] . '" width="' . $width . '" height="80" style="margin-left: 112px" />';
        
        $data['description[' . $image_info['language_id'] . ']'] = $image_info['description'];
        $data['image_url[' . $image_info['language_id'] . ']'] = $image_info['image_url'];
        $data['slide_image' . $image_info['language_id']] = $slide_image; 
      }
    }
    
    return $data;
  }
  
  public function get_totals()
  {
    return $this->db->count_all('slide_images');
  }
}