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
 * @filesource
 */

class Templates_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get template data through templates_id
     *
     * @param int $templates_id
     * @return array template data
     */
    public function get_template_data($templates_id)
    {
        $result = $this->db->select('*')->from('templates')->where('id', $templates_id)->get();

        $data = FALSE;
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();

            $data['params'] = json_decode($data['params'], TRUE);
        }

        return $data;
    }

    /**
     * Get template data through template code
     *
     * @param string $code template code
     * @return array template data
     */
    public function get_template_data_via_code($code)
    {
        $result = $this->db->select('*')->from('templates')->where('code', $code)->get();

        $data = FALSE;
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();
        }

        return $data;
    }

    /**
     * Install template
     *
     * @param array $data
     */
    public function install($data) {
        if ($this->is_installed($data['code']) === FALSE)
        {
            //insert template info into database
            //
            $template = array(
      	'title' => $data['title'], 
      	'code' => $data['code'],
      	'author_name' => $data['author_name'],
        'author_www' => $data['author_www'],
      	'markup_version' => $data['markup_version'],
      	'css_based' => $data['css_based'],
      	'medium' => $data['medium'],
        'params' => json_encode($data['params']));

            $this->db->insert('templates', $template);

            //get template id
            //
            $templates_id = $this->db->insert_id();

            //insert web layout data
            //
            if (isset($data['web_layout']['modules']) && count($data['web_layout']['modules']) > 0) {
                foreach ($data['web_layout']['modules'] as $module) {
                    $this->db->insert('templates_modules', array(
          	'templates_id' => $templates_id, 
          	'medium' => $module['medium'],
          	'module' => $module['code'],
            'status' => '1',
          	'content_page' => $module['page'],
          	'content_group' => $module['group'],
          	'sort_order' => $module['sort-order'],
            'params' => json_encode($module['params'])));
                }
            }

            //insert mobile layout data
            //
            if (isset($data['mobile_layout']['modules']) && count($data['mobile_layout']['modules']) > 0) {
                foreach ($data['mobile_layout']['modules'] as $module) {
                    $this->db->insert('templates_modules', array(
          	'templates_id' => $templates_id, 
          	'medium' => $module['medium'],
          	'module' => $module['code'],
            'status' => '1',
          	'content_page' => $module['page'],
          	'content_group' => $module['group'],
          	'sort_order' => $module['sort-order'],
            'params' => json_encode($module['params'])));
                }
            }
        }

        return TRUE;
    }

    /**
     * Check whether the template is installed
     *
     * @param string $code template code
     */
    public function is_installed($code)
    {
        $result = $this->db->select('*')->from('templates')->where('code', $code)->get();

        $installed = FALSE;
        if ($result->num_rows() > 0)
        {
            $installed = TRUE;
        }

        return $installed;
    }

    public function delete_template_module ($id) {
        return $this->db->delete('templates_modules', array('id' => $id));
    }

    public function update_template_module($module) {
        $data = array('page_specific' => $module['page_specific'],
                  'status' => $module['status'],
                  'content_page' => $module['content_page'],
                  'sort_order' => $module['sort_order'],
                  'params' => json_encode($module['params']));

        $this->db->where('id', $module['id']);
        return $this->db->update('templates_modules', $data);
    }

    public function insert_template_module($data) {
        $this->db->insert('templates_modules', $data);

        $id = $this->db->insert_id();

        return $id;
    }

    public function get_code($templates_id) {
        $result = $this->db->select('*')->from('templates')->where('id', $templates_id)->get();

        $code = FALSE;
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();

            $code = $data['code'];
        }

        return $code;
    }

    public function remove($code) {
        $result = $this->db->select('*')->from('templates')->where('code', $code)->get();

        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();

            //delete template modules
            $this->db->where('templates_id', $data['id']);
            $this->db->delete('templates_modules');


            //delete template
            $this->db->where('id', $data['id']);
            $this->db->delete('templates');

            return TRUE;
        }

        return FALSE;
    }

    public function set_default($code)
    {
        $this->db->update('configuration', array('configuration_value' => $code), array('configuration_key' => 'DEFAULT_TEMPLATE'));
    }


    public function save_template_params($templates_id, $data) {
        $this->db->where('id', $templates_id);

        return $this->db->update('templates', array('params' => json_encode($data)));
    }

    public function get_medium_modules($code, $medium)
    {
        $result = $this->db->select('m.*')
        ->from('templates_modules m, templates t')
        ->where('t.id = m.templates_id')
        ->where('t.code', $code)
        ->where('m.medium', $medium)
        ->get();

        $modules = array();
        if ($result->num_rows() > 0)
        {
            foreach ($result->result_array() as $row) {
                $modules[] = array(
        	'id' => $row['id'],
        	'templates_id' => $row['templates_id'],
        	'medium' => $row['medium'],
        	'module' => $row['module'],
        	'status' => $row['status'],
        	'content_page' => $row['content_page'],
        	'content_group' => $row['content_group'],
        	'page_specific' => $row['page_specific'],
        	'sort_order' => $row['sort_order'],
        	'params' => json_decode($row['params'], TRUE)
                );
            }
        }

        return $modules;
    }

    public function update_template_module_group($module_id, $group)
    {
        $this->db->where('id', $module_id);

        return $this->db->update('templates_modules', array('content_group' => $group));
    }
}

/* End of file templates_model.php */
/* Location: ./system/modules/templates/models/templates_model.php */