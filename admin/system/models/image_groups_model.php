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

class Image_Groups_Model extends CI_Model
{
  public function get_image_groups($start, $limit)
  {
    $Qgroups = $this->db
    ->select('id, title')
    ->from('products_images_groups')
    ->where('language_id', lang_id())
    ->order_by('title')
    ->limit($limit, $start)
    ->get();
    
    return $Qgroups->result_array();
  }
  
  public function save($id = NULL, $data, $default = FALSE)
  {
    if (is_numeric($id))
    {
      $group_id = $id;
    }
    else
    {
      $Qgroup = $this->db
      ->select_max('id')
      ->from('products_images_groups')
      ->get();
      
      $group = $Qgroup->row_array();
      
      $group_id = $group['id'] + 1;
    }
    
    $error = FALSE;
    
    $this->db->trans_begin();
    
    foreach(lang_get_all() as $l)
    {
      $data['title'] = $data['title'][$l['id']];
      $data['force_size'] = $data['force_size'] === TRUE ? 1 : 0;
      
      if (is_numeric($id))
      {
        $this->db->update('products_images_groups', $data, array('id' => $id, 'language_id' => $l['id']));
      }
      else
      {
        $data['id'] = $group_id;
        $data['language_id'] = $l['id'];
        
        $this->db->insert('products_images_groups', $data);
      }
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
        break;
      }
    }
    
    if ($error === FALSE)
    {
      if ($default === TRUE)
      {
        $this->db->update('configuration', array('configuration_value' => $group_id), array('configuration_key' => 'DEFAULT_IMAGE_GROUP_ID'));
        
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
        }
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function delete($id)
  {
    $this->db->delete('products_images_groups', array('id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_data($image_group_id)
  {
    $Qgroup = $this->db
    ->select('*')
    ->from('products_images_groups')
    ->where('id', $image_group_id)
    ->get();
    
    return $Qgroup->result_array();
  }
  
  public function get_total()
  {
    return $this->db->count_all('products_images_groups');
  }
}




/* End of file image_groups.php */
/* Location: ./system/modules/image_groups/models/image_groups_model.php */