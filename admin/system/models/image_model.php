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

class Image_Model extends CI_Model {
  public function __construct()
  {
    parent::__construct();
  }
  
  public function get_groups()
  {
    $Qgroups = $this->db
    ->select('*')
    ->from('products_images_groups')
    ->where('language_id', lang_id())
    ->get();
    
    return $Qgroups->result_array();
  }

}

/* End of file image_model.php */
/* Location: ./application/models/image_model.php */