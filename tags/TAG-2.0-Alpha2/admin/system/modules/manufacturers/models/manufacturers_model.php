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

class Manufacturers_Model extends CI_Model
{
  public function get_manufacturers($start, $limit)
  {
    $Qmanufacturers = $this->db
    ->select('manufacturers_id, manufacturers_name, manufacturers_image, date_added, last_modified')
    ->from('manufacturers')
    ->order_by('manufacturers_name')
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qmanufacturers->result_array();
  }
  
  public function get_sum_clicks($id)
  {
    $Qclicks = $this->db
    ->select_sum('url_clicked', 'total')
    ->from('manufacturers_info')
    ->where('manufacturers_id', $id)
    ->get();
    
    $clicks = $Qclicks->row_array();
    
    return $clicks['total'];
  }
  
  public function delete($id, $delete_image = FALSE, $delete_products = FALSE)
  {
    if ($delete_image === TRUE )
    {
      $Qimage = $this->db
      ->select('manufacturers_image')
      ->from('manufacturers')
      ->where('manufacturers_id', $id)
      ->get();
      
      $image = $Qimage->row_array();
      
      $Qimage->free_result();
      
      if (!empty($image))
      {
        if (file_exists(ROOTPATH . 'images/manufacturers/' . $image['manufacturers_image']))
        {
          @unlink(ROOTPATH . 'images/manufacturers/' . $image['manufacturers_image']);
        
        }
      }
    }
    
    $this->db->delete('manufacturers', array('manufacturers_id' => $id));
    $this->db->delete('manufacturers_info', array('manufacturers_id' => $id));
    
    if ($delete_products === TRUE)
    {
      $Qproducts = $this->db
      ->select('products_id')
      ->from('products')
      ->where('manufacturers_id', $id)
      ->get();
      
      $products = $Qproducts->result_array();
      
      $Qproducts->free_result();
      
      if (!empty($products))
      {
        foreach($products as $product)
        {
          $this->products->get_model()->delete_product($product['products_id']);
        }
      }
    }
    else
    {
      $this->db->update('products', array('manufacturers_id' => NULL), array('manufacturers_id' => $id));
    }
    
    if ($this->cache->get('box-manufacturers') !== FALSE)
    {
      $this->cache->delete('box-manufacturers');
    }
    
    if ($this->cache->get('sefu-manufacturers') !== FALSE)
    {
      $this->cache->delete('sefu-manufacturers');
    }
    
    return TRUE;
  }
  
  public function save($id = NULL, $data)
  {
    $error = FALSE;
    
    $this->db->trans_begin();
    
    if (is_numeric($id))
    {
      $this->db->update('manufacturers', array('manufacturers_name' => $data['name'], 'last_modified' => date('Y-m-d H:i:s')), array('manufacturers_id' => $id));
    }
    else
    {
      $this->db->insert('manufacturers', array('manufacturers_name' => $data['name'], 'date_added' => date('Y-m-d H:i:s')));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $manufacturers_id = $id ? $id : $this->db->insert_id();
      
      $config['upload_path'] = ROOTPATH . 'images/manufacturers/';
      $config['allowed_types'] = 'gif|jpg|png';
      $this->upload->initialize($config);
      
      if ($this->upload->do_upload($data['image']))
      {
        $manufacturers_image_info = $this->upload->data();
        
        $this->db->update('manufacturers', array('manufacturers_image' => $manufacturers_image_info['file_name']), array('manufacturers_id' => $manufacturers_id));
        
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
        }
      }
    }
    else
    {
      $error = TRUE;
    }
    
    if ($error === FALSE)
    {
      foreach(lang_get_all() as $l)
      {
        $manufacturers_info = array('manufacturers_url' => $data['url'][$l['id']], 
                                    'manufacturers_friendly_url' => $data['friendly_url'][$l['id']], 
                                    'manufacturers_page_title' => $data['page_title'][$l['id']], 
                                    'manufacturers_meta_keywords' => $data['meta_keywords'][$l['id']], 
                                    'manufacturers_meta_description' => $data['meta_description'][$l['id']]);
        
        if (is_numeric($id))
        {
          $this->db->update('manufacturers_info', $manufacturers_info, array('manufacturers_id' => $manufacturers_id, 'languages_id' => $l['id']));
        }
        else
        {
          $manufacturers_info['manufacturers_id'] = $manufacturers_id;
          $manufacturers_info['languages_id'] = $l['id'];
          
          $this->db->insert('manufacturers_info', $manufacturers_info);
        }
        
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
          break;
        }
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->trans_commit();
      
      if ($this->cache->get('box-manufacturers') !== FALSE)
      {
        $this->cache->delete('box-manufacturers');
      }
      
      if ($this->cache->get('sefu-manufacturers') !== FALSE)
      {
        $this->cache->delete('sefu-manufacturers');
      }
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_data($id, $language_id = null)
  {
    if (empty($language_id))
    {
      $language_id = lang_id();
    }
    
    $Qmanufacturers = $this->db
    ->select('m.*, mi.*')
    ->from('manufacturers m')
    ->join('manufacturers_info mi', 'm.manufacturers_id = mi.manufacturers_id')
    ->where(array('m.manufacturers_id' => $id, 'mi.languages_id' => $language_id))
    ->get();
    
    $data = $Qmanufacturers->row_array();
    
    $Qmanufacturers->free_result();
    
    $Qclicks = $this->db
    ->select_sum('url_clicked', 'total')
    ->from('manufacturers_info')
    ->where('manufacturers_id', $id)
    ->get();
    
    $clicks = $Qclicks->row_array();
    $Qclicks->free_result();
    
    $data['url_clicks'] = $clicks['total'];
    
    $data['products_count'] = $this->db->where('manufacturers_id', $id)->from('products')->count_all_results();
    
    return $data;
  }
  
  public function get_info($id)
  {
    $Qmanufacturer = $this->db
    ->select('languages_id, manufacturers_url, manufacturers_friendly_url, manufacturers_page_title, manufacturers_meta_keywords, manufacturers_meta_description')
    ->from('manufacturers_info')
    ->where('manufacturers_id', $id)
    ->get();
    
    return $Qmanufacturer->result_array();
  }
  
  public function get_manufacturers_data()
  {
    $Qmanufacturers = $this->db
    ->select('*')
    ->from('manufacturers')
    ->get();
    
    return $Qmanufacturers->result_array();
  }
  
  public function get_totals()
  {
    return $this->db->count_all('manufacturers');
  }
}

/* End of file manufacturers_model.php */
/* Location: ./system/modules/manufacturers/models/manufacturers_model.php */