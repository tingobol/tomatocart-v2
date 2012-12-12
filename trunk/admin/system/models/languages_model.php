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

class Languages_Model extends CI_Model
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
    function load($group = 'general')
    {
        $definitions = array();
        $this->db->select('*')->from('languages_definitions')->where('languages_id', lang_id())->where('content_group', $group);
        $qry = $this->db->get();

        foreach ($qry->result() as $key => $row){
            $definitions[$row->definition_key] = $row->definition_value;
        }

        return $definitions;
    }

    function get_languages()
    {
        $qry = $this->db->select('*')->from('languages')->order_by(' sort_order, name')->get();

        $languages = array();
        foreach ($qry->result_array() as $row){
            $languages[$row['code']] = array( 'id' => $row['languages_id'],
                                        'code' => $row['code'],
                                        'country_iso' => strtolower(substr($row['code'], 3)),
                                        'name' => $row['name'],
                                        'locale' => $row['locale'],
                                        'charset' => $row['charset'],
                                        'date_format_short' => $row['date_format_short'],
                                        'date_format_long' => $row['date_format_long'],
                                        'time_format' => $row['time_format'],
                                        'text_direction' => $row['text_direction'],
                                        'currencies_id' => $row['currencies_id'],
                                        'numeric_separator_decimal' => $row['numeric_separator_decimal'],
                                        'numeric_separator_thousands' => $row['numeric_separator_thousands'],
                                        'parent_id' => $row['parent_id']);
        }

        return $languages;
    }

    /**
     * Insert definition
     *
     * @access public
     * @param $definition
     * @param $table
     * @return boolean
     */
    function insert_definition ($definition, $table = 'languages_definitions') {
        return $this->db->insert($table, $definition);
    }
    

    /**
     * Remove definition
     *
     * @access public
     * @param $definition
     * @param $table
     * @return boolean
     */
    function remove_definition ($definition, $table = 'languages_definitions') {
        return $this->db->delete($table, array('content_group' => $definition['content_group'], 'definition_key' => $definition['definition_key'], 'languages_id' => $definition['languages_id'])); 
    }
}
/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */