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
 * @filesource .system/modules/products_expected/models/products_expected_model.php
 */

Class Products_Expected_Model extends CI_Model
{
  public function get_products($start, $limit)
  {
    $Qproducts = $this->db
    ->select('p.products_id, p.products_date_available, pd.products_name')
    ->from('products p')
    ->join('products_description pd', 'p.products_id = pd.products_id')
    ->where('pd.language_id', lang_id())
    ->where('p.products_date_available IS NOT NULL')
    ->order_by('p.products_date_available')
    ->limit($limit, $start)
    ->get();
    
    return $Qproducts->result_array();
  }
  
  public function save_date_available($id, $data)
  {
    $this->db->update('products', array('products_date_available' => date('Y-m-d') < $data['date_available'] ? $data['date_available'] : 'null', 
                                        'products_last_modified' => date('Y-m-d H:i:s')), 
                                  array('products_id' => $id));
                                  
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_data($id) {
    $Qproducts = $this->db
    ->select('p.*, pd.*, ptoc.*')
    ->from('products p')
    ->join('products_description pd', 'p.products_id = pd.products_id', 'left')
    ->join('products_to_categories ptoc', 'ptoc.products_id = p.products_id', 'left')
    ->where(array('p.products_id' => $id, 'pd.language_id' => lang_id()))
    ->get();
    
    return $Qproducts->row_array();
  }
  
  public function get_total()
  {
    $Qproducts = $this->db
    ->select('p.products_id')
    ->from('products p')
    ->join('products_description pd', 'p.products_id = pd.products_id')
    ->where('pd.language_id', lang_id())
    ->where('p.products_date_available IS NOT NULL')
    ->get();
    
    return $Qproducts->num_rows();
  }
}


/* End of file products_expected_model.php */
/* Location: ./system/modules/products_expected/models/products_expected_model.php */