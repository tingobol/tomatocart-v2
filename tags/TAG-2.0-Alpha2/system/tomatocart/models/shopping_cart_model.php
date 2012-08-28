<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize, creative CMS
 *
 * @package   Ionize
 * @author    Ionize Dev Team
 * @license   http://ionizecms.com/doc-license
 * @link    http://ionizecms.com
 * @since   Version 0.9.0
 */

// ------------------------------------------------------------------------

/**
 * Ionize, creative CMS Settings Model
 *
 * @package   Ionize
 * @subpackage  Models
 * @category  Admin settings
 * @author    Ionize Dev Team
 */

class Shopping_Cart_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_contents($customers_id)
  {
    $result = $this->db->select('products_id, customers_basket_quantity')->from('customers_basket')->where('customers_id', $customers_id)->get();

    $contents = FALSE;
    if ($qry->num_rows() > 0)
    {
      foreach ($result->result_array() as $row)
      {
        $contents['products_id'] = $row['customers_basket_quantity'];
      }
    }

    return $contents;
  }

  public function update_content($customers_id, $products_id, $quantity)
  {
    $this->db->set('customers_basket_quantity', $quantity);
    $this->db->where('customers_id', $customers_id);
    $this->db->where('products_id', $products_id);
    $this->db->update('customers_basket');
  }

  public function insert_content($customers_id, $products_id, $quantity)
  {
    $this->db->set('customers_basket_quantity', $quantity);
    $this->db->set('customers_id', $customers_id);
    $this->db->set('products_id', $products_id);
    $this->db->insert('customers_basket');
  }

  public function delete_content($products_id)
  {
    $this->db->where('products_id', $products_id)->delete('customers_basket');
  }

  public function delete($customers_id)
  {
    $this->db->where('customers_id', $customers_id)->delete('customers_basket');
  }
}

/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */