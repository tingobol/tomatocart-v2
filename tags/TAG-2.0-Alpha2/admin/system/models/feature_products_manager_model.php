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
 * @filesource ./system/modules/feature_products_manager/models/feature_products_manager_model.php
 */

class Feature_Products_Manager_Model extends CI_Model
{
  public function get_products($start, $limit, $in_categories = array())
  {
    $this->query($in_categories);
  
    $Qproducts = $this->db
    ->order_by('pf.sort_order')
    ->limit($limit, $start)
    ->get();
    
    return $Qproducts->result_array();
  }
  
  public function delete($id)
  {
    $this->db->delete('products_frontpage', array('products_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function save($id, $value)
  {
    $this->db->update('products_frontpage', array('sort_order' => $value), array('products_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_total($in_categories)
  {
    $this->query($in_categories);
    
    $Qtotal = $this->db->get();
    
    return $Qtotal->num_rows();
  }
  
  private function query($in_categories)
  {
    $this->db
    ->select('pd.products_id, pd.products_name, pf.sort_order')
    ->from('products_frontpage pf')
    ->join('products_description pd', 'pf.products_id = pd.products_id');
    
    if (!empty($in_categories))
    {
      $this->db
      ->join('products_to_categories p2c', 'p2c.products_id = pf.products_id')
      ->where_in('p2c.categories_id', $in_categories);
    }
  }
}

/* End of file feature_products_manager_model.php */
/* Location: ./system/modules/feature_products_manager/models/feature_products_manager_model.php */
