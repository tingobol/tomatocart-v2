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

class Currencies_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function get_all()
  {
    $result = $this->db->select('*')->from('currencies')->get();
    
    $currencies = FALSE;
    foreach($result->result_array() as $row)
    {
      $currencies[$row['code']] = array(
        'id' => $row['currencies_id'],
        'title' => $row['title'],
        'symbol_left' => $row['symbol_left'],
        'symbol_right' => $row['symbol_right'],
        'decimal_places' => $row['decimal_places'],
        'value' => $row['value']);
    }

    return $currencies;
  }
}

/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */