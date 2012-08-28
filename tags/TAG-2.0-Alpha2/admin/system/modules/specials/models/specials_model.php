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
 * @filesource .system/modules/specials/models/specials.php
 */

class Specials_Model extends CI_Model
{
  public function get_specials($params)
  {
    $Qspecials = $this->get_query($params)
    ->order_by('pd.products_name')
    ->limit($params['limit'], $params['start'] > 0 ? $params['start'] - 1 : $params['start'])
    ->get();
    
    return $Qspecials->result_array();
  }
  
  public function get_total($params)
  {
    $Qspecials = $this->get_query($params)->get();
    
    return $Qspecials->num_rows();
  }
  
  private function get_query($params)
  {
    if (isset($params['in_categories']) && !empty($params['in_categories']))
    {
      $this->db
      ->select('p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.expires_date, s.date_status_change, s.status')
      ->from('specials s')
      ->join('products p', 'p.products_id = s.products_id')
      ->join('products_description pd', 'p.products_id = pd.products_id')
      ->join('products_to_categories p2c', 'p.products_id = p2c.products_id')
      ->where('pd.language_id', lang_id())
      ->where_in('p2c.categories_id', $params['in_categories']);
    }
    else
    {
      $this->db
      ->select('p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.expires_date, s.date_status_change, s.status')
      ->from('specials s')
      ->join('products p', 'p.products_id = s.products_id')
      ->join('products_description pd', 'p.products_id = pd.products_id')
      ->where('pd.language_id', lang_id());
    }
    
    if (isset($params['search']) && !empty($params['search']))
    {
      $this->db->like('pd.products_name', $params['search']);
    }
    
    if (isset($params['manufacturers_id']) && !empty($params['manufacturers_id']))
    {
      $this->db->where('p.manufacturers_id', $params['manufacturers_id']);
    }
    
    return $this->db;
  }
  
  public function save($id = NULL, $data)
  {
    $error = FALSE;
    
    $Qproduct = $this->db
    ->select('products_price')
    ->from('products')
    ->where('products_id', $data['products_id'])
    ->get();
    
    $product = $Qproduct->row_array();
    
    $specials_price = $data['specials_price'];
    
    if (substr($specials_price, -1) == '%')
    {
      $specials_price = $product['products_price'] - (((double)$specials_price / 100) * $product['products_price']);
    }
    
    if (($specials_price < '0.00') || (isset($product['products_price']) && $specials_price >= $product['products_price']))
    {
      $error = TRUE;
    }
    
    if ($data['expires_date'] < $data['start_date'])
    {
      $error = TRUE;
    }
    
    if ($error === FALSE)
    {
      if (is_numeric($id))
      {
        $this->db->update('specials', array('specials_new_products_price' => $specials_price, 
                                            'specials_last_modified' => date('Y-m-d H:i:s'), 
                                            'expires_date' => $data['expires_date'], 
                                            'start_date' => $data['start_date'], 
                                            'status' => $data['status']), 
                                      array('specials_id' => $id));
      }
      else
      {
        $this->db->insert('specials', array('products_id' => $data['products_id'], 
                                            'specials_new_products_price' => $specials_price, 
                                            'specials_date_added' => date('Y-m-d H:i:s'), 
                                            'expires_date' => $data['expires_date'], 
                                            'start_date' => $data['start_date'], 
                                            'status' => $data['status']));
      }
      
      if ($this->db->affected_rows() < 1)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      if ($this->cache->get('product-' . $data['products_id']))
      {
        $this->cache->delete('product-' . $data['products_id']);
      }
      
      if ($this->cache->get('product-specials-' . $data['products_id']))
      {
        $this->cache->delete('product-specials-' . $data['products_id']);
      }
      
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_products($start, $limit)
  {
    $Qproducts = $this->db
    ->select('p.products_id, pd.products_name, p.products_tax_class_id')
    ->from('products p')
    ->join('products_description pd', 'p.products_id = pd.products_id')
    ->where(array('pd.language_id' => lang_id(), 'p.products_type !=' => PRODUCT_TYPE_GIFT_CERTIFICATE))
    ->order_by('pd.products_name')
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qproducts->result_array();
  }
  
  public function delete($id)
  {
    $Qproduct = $this->db
    ->select('products_id')
    ->from('specials')
    ->where('specials_id', $id)
    ->get();
    
    $product = $Qproduct->row_array();
    $Qproduct->free_result();
    
    $products_id = $product['products_id'];
    
    $this->db->delete('specials', array('specials_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      if ($this->cache->get('product-' . $products_id))
      {
        $this->cache->delete('product-' . $products_id);
      }
      
      if ($this->cache->get('product-specials-' . $products_id))
      {
        $this->cache->delete('product-specials-' . $products_id);
      }
      
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_data($id)
  {
    $Qspecial = $this->db
    ->select('p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.start_date, s.expires_date, s.date_status_change, s.status')
    ->from('specials s')
    ->join('products p', 's.products_id = p.products_id')
    ->join('products_description pd', 'p.products_id = pd.products_id')
    ->where(array('s.specials_id' => $id, 'pd.language_id' => lang_id()))
    ->limit(1)
    ->get();
    
    return $Qspecial->row_array();
  }
  
  public function get_total_products()
  {
    return $this->db
            ->where('products_type !=', PRODUCT_TYPE_GIFT_CERTIFICATE)
            ->from('products')
            ->count_all_results();
  }
}




/* End of file specials.php */
/* Location: ./system/modules/specials/controllers/specials.php */