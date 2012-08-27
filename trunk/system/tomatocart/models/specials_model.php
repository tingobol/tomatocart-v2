<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize, creative CMS
 *
 * @package		Ionize
 * @author		Ionize Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 0.9.0
 */

// ------------------------------------------------------------------------

/**
 * Ionize, creative CMS Settings Model
 *
 * @package		Ionize
 * @subpackage	Models
 * @category	Admin settings
 * @author		Ionize Dev Team
 */

class Specials_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_special_products() {
    $result = $this->db->select('p.products_id, p.products_price, p.products_tax_class_id, pd.products_name, pd.products_keyword, s.specials_new_products_price as special_price, i.image ')
                    ->from('products p')
                    ->join('products_images i', 'p.products_id = i.products_id', 'left')
                    ->join('products_description pd', 'p.products_id = pd.products_id', 'inner')
                    ->join('specials s', 's.products_id = p.products_id', 'inner')
                    ->where('p.products_status = 1')
                    ->where('pd.language_id', lang_id())
                    ->where('i.default_flag = 1')
                    ->where('s.status = 1')
                    ->order_by('s.specials_date_added desc')
                    ->limit(config('MAX_DISPLAY_SPECIAL_PRODUCTS'))
                    ->get();
                    
    $products = array();
    if ($result->num_rows() > 0)
    {
      $products = $result->result_array();
    }

    return $products;
  }
}
/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */