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

class Weight_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function get_title($id)
  {
    $result = $this->db->select('weight_class_title')->from('weight_class')->where('weight_class_id', $id)->where('language_id', lang_id())->get();

    $title = FALSE;
    if ($result->num_rows() > 0)
    {
      $data = $result->row_array();
      $tile = $data['weight_class_title'];
    }

    return $title;
  }

  function get_rules()
  {
    $weight_classes = FALSE;

    //weight class rules
    $result = $this->db->select('r.weight_class_from_id, r.weight_class_to_id, r.weight_class_rule')->from('weight_classes_rules as r')->join('weight_classes as c', 'c.weight_class_id = r.weight_class_from_id', 'left')->get();
    if ($result->num_rows() > 0)
    {
      foreach($result->result_array() as $row)
      {
        $weight_classes[$row['weight_class_from_id']][$row['weight_class_to_id']] = $row['weight_class_rule'];
      }
    }

    //weight classes
    $result = $this->db->select('weight_class_id, weight_class_key, weight_class_title')->from('weight_classes')->where('language_id', lang_id())->get();
    if ($result->num_rows() > 0)
    {
      foreach($result->result_array() as $row)
      {
        $weight_classes[$row['weight_class_id']]['key'] = $row['weight_class_key'];
        $weight_classes[$row['weight_class_id']]['title'] = $row['weight_class_title'];
      }
    }

    return $weight_classes;
  }

  function get_classes()
  {
    $result = $this->db->select('weight_class_id, weight_class_title')->from('weight_class')->where('language_id', lang_id())->order_by('weight_class_title')->get();

    $weight_classes = FALSE;
    if ($result->num_rows() > 0)
    {
      $weight_classes = $result->result_array();
    }

    return $weight_classes;
  }
}

/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */