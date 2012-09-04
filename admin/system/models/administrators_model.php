<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Administrators Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Administrators_Model extends CI_Model
{
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
      
  // ------------------------------------------------------------------------

    /**
     * Get the administrators
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_administrators($start, $limit)
    {
        $result = $this->db
        ->select('id, user_name, email_address')
        ->from('administrators')
        ->order_by('user_name')
        ->limit($limit, $start)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------

    /**
     * Save the administrator
     *
     * @access public
     * @param $id
     * @param $data
     * @param $modules
     * @return boolean
     */
    public function save($id = NULL, $data, $modules = NULL)
    {
        $this->load->helper('email');
        $this->load->library('encrypt');
        
        $error = FALSE;

        //check email
        if (valid_email($data['email_address']))
        {
            $this->db
            ->select('id')
            ->from('administrators')
            ->where('email_address', $data['email_address']);

            if (is_numeric($id))
            {
                $this->db->where('id !=', $id);
            }

            $result = $this->db->get();

            if ($result->num_rows() > 0)
            {
                return -4;
            }
        }
        else
        {
            return -3;
        }

        //check username
        $this->db->select('id')->from('administrators')->where('user_name', $data['username']);

        if (is_numeric($id))
        {
            $this->db->where('id !=', $id);
        }

        $result = $this->db->limit(1)->get();

        if ($result->num_rows() == 1)
        {
            return -2;
        }

        if ($error === FALSE)
        {
            $this->db->trans_begin();

            $admin_data = array('user_name' => $data['username'], 'email_address' => $data['email_address']);

            if (is_numeric($id))
            {
                if (isset($data['password']) && !empty($data['password']))
                {
                    $admin_data['user_password'] = $this->encrypt->encode(trim($data['password']));
                }

                $this->db->update('administrators', $admin_data, array('id' => $id));
            }
            else
            {
                $admin_data['user_password'] = $this->encrypt->encode(trim($data['password']));

                $this->db->insert('administrators', $admin_data);
            }

            if ($this->db->trans_status() === TRUE)
            {
                if (!is_numeric($id))
                {
                    $id = $this->db->insert_id();
                }
            }
            else
            {
                $error = TRUE;
            }
        }

        if ($error === FALSE)
        {
            if (!empty($modules))
            {
                if (in_array('*', $modules))
                {
                    $modules = array('*');
                }

                foreach($modules as $module)
                {
                    $result = $this->db
                    ->select('administrators_id')
                    ->from('administrators_access')
                    ->where(array('administrators_id' => $id, 'module' => $module))
                    ->limit(1)
                    ->get();

                    if ($result->num_rows() < 1)
                    {
                        $this->db->insert('administrators_access', array('administrators_id' => $id, 'module' => $module));

                        if ($this->db->trans_status() === FALSE)
                        {
                            $error = TRUE;
                            break;
                        }
                    }
                    
                    $result->free_result();
                }
            }
        }

        //delete the original modules which are not in the modules
        if ($error === FALSE)
        {
            $this->db->where('administrators_id', $id);

            if (!empty($modules))
            {
                $this->db->where_not_in('module', $modules);
            }

            $this->db->delete('administrators_access');

            if ($this->db->trans_status() === FALSE)
            {
                $error = TRUE;
            }
        }

        if ($error === FALSE)
        {
            $this->db->trans_commit();

            return 1;
        }
        else
        {
            $this->db->trans_rollback();

            return -1;
        }
    }
    
// ------------------------------------------------------------------------
  
    /**
     * Delete the administrator
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->trans_begin();

        $this->db->delete('administrators_access', array('administrators_id' => $id));

        if ($this->db->trans_status() === TRUE)
        {
            $this->db->delete('administrators', array('id' => $id));
        }

        if ($this->db->trans_status() === TRUE)
        {
            $this->db->trans_commit();

            return TRUE;
        }

        $this->db->trans_rollback();

        return FALSE;
    }
    
// ------------------------------------------------------------------------

    /**
     * Get the data of the administrator with the id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_data($id)
    {
        $result = $this->db
        ->select('id, user_name, email_address')
        ->from('administrators')
        ->where('id', $id)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->row_array();
        }

        return NULL;
    }
    
// ------------------------------------------------------------------------

    public function get_admin($email)
    {
        $Qadmin = $this->db
        ->select('id, user_name, email_address')
        ->from('administrators')
        ->where('email_address', $email)
        ->get();

        return $Qadmin->row_array();
    }
    
// ------------------------------------------------------------------------

    public function update_password($email, $password)
    {
        $this->db->update('administrators', array('user_password' => $password), array('email_address' => $email));

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }

        return FALSE;
    }
    
// ------------------------------------------------------------------------

    /**
     * Get the modules that the administrator could access
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_modules($id)
    {
        $result = $this->db
        ->select('module')
        ->from('administrators_access')
        ->where('administrators_id', $id)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }

        return NULL;
    }
    
// ------------------------------------------------------------------------

    /**
     * 
     * @param $email
     */
    public function check_email($email)
    {
        $result = $this->db->select('id')->from('administrators')->where('email_address', $email)->get();

        if ($result->num_rows() > 0)
        {
            return TRUE;
        }

        return FALSE;
    }
    
// ------------------------------------------------------------------------

    /**
     * Get the total number of administrators
     *
     * @access public
     * @return int
     */
    public function get_total()
    {
        return $this->db->count_all('administrators');
    }
}

/* End of file administrators_model.php */
/* Location: ./system/models/administrators_model.php */