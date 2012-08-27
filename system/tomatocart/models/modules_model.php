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

class Modules_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function get_modules($group, $page, $medium)
  {
    $data = array();

    $content_pages = array('*', $group . '/*', $group . '/' . $page);

    //page has specific module
    $result = $this->db->select('id, content_group, module, params')->from('templates_modules')->where('page_specific', '1')->where('templates_id', $this->template->get_id())->where_in('content_page', $content_pages)->where('medium', $medium)->where('status', 1)->order_by('content_group')->order_by('sort_order')->get();
    if ($result->num_rows() > 0)
    {
      foreach($result->result_array() as $row)
      {
        $data[$row['content_group']][] = array('id' => $row['id'], 'module' => $row['module'], 'params' => json_decode($row['params'], TRUE));
      }
    } 
    //get all page modules
    else 
    {
      $data = array();

      $result = $this->db->select('id, content_group, content_page, module, params')->from('templates_modules')->where('templates_id', $this->template->get_id())->where_in('content_page', $content_pages)->where('medium', $medium)->where('status', 1)->order_by('content_group')->order_by('sort_order')->get();
      if ($result->num_rows() > 0)
      {
        foreach($result->result_array() as $row)
        {
          $data[$row['content_group']][] = array('id' => $row['id'], 'module' => $row['module'], 'params' => $row['params'], 'page' => $row['content_page']);
        }
      }
    }
    
    return $data;
  }
}

/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */