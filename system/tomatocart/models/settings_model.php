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

class Settings_Model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }


  /**
   * Get the settings
   * Don't retrieves the language depending settings
   *
   * @return	The settings array
   */
  function get_settings()
  {
    $settings = array();

    $result = $this->db->select("configuration_key, configuration_value")->from("configuration")->get();
    foreach ($result->result_array() as $row){
      $settings[$row['configuration_key']] = $row['configuration_value'];
    }
    
    return $settings;
  }
}
/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */