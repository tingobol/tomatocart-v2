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

class Template_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();


  }

  public function get_modules($customers_id)
  {
  }

  public function get_templates()
  {
    $result = $this->db->select('id, code, title')->from('templates')->get();

    $templates = FALSE;
    if ($result->num_rows() > 0)
    {
      $templates = $result->result_array();
    }

    return $templates;
  }
}

/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */