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
 * Manufacturers Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Manufacturers_Model extends CI_Model
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
     * Get the manufacturers
     *
     * @access public
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function get_manufacturers($start, $limit)
    {
        $result = $this->db
        ->select('manufacturers_id, manufacturers_name, manufacturers_image, date_added, last_modified')
        ->from('manufacturers')
        ->order_by('manufacturers_name')
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
     * Get the total clicks of the manufacture with id
     *
     * @access public
     * @param $id
     * @return int
     */
    public function get_sum_clicks($id)
    {
        $result = $this->db
        ->select_sum('url_clicked', 'total')
        ->from('manufacturers_info')
        ->where('manufacturers_id', $id)
        ->get();
        
        $clicks = $result->row_array();
        
        return $clicks['total'];
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Delete the manufacturer with id
     *
     * @access public
     * @param $id
     * @param $delete_image
     * @param $delete_products
     * @return boolean
     */
    public function delete($id, $delete_image = FALSE, $delete_products = FALSE)
    {
        $this->load->model('products_model');
        
        if ($delete_image === TRUE )
        {
            $result = $this->db
            ->select('manufacturers_image')
            ->from('manufacturers')
            ->where('manufacturers_id', $id)
            ->get();
            
            $image = $result->row_array();
            
            $result->free_result();
            
            if (!empty($image))
            {
                if (file_exists(ROOTPATH . 'images/manufacturers/' . $image['manufacturers_image']))
                {
                    @unlink(ROOTPATH . 'images/manufacturers/' . $image['manufacturers_image']);
                
                }
            }
        }
        
        $this->db->delete('manufacturers', array('manufacturers_id' => $id));
        $this->db->delete('manufacturers_info', array('manufacturers_id' => $id));
        
        if ($delete_products === TRUE)
        {
            $result = $this->db
            ->select('products_id')
            ->from('products')
            ->where('manufacturers_id', $id)
            ->get();
            
            $products = $result->result_array();
            
            $result->free_result();
            
            if (!empty($products))
            {
                foreach($products as $product)
                {
                    $this->products_model->delete_product($product['products_id']);
                }
            }
        }
        else
        {
            $this->db->update('products', array('manufacturers_id' => NULL), array('manufacturers_id' => $id));
        }
        
        return TRUE;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Save the manufacturer
     *
     * @access public
     * @param $id
     * @param $data
     * @return boolean
     */
    public function save($id = NULL, $data)
    {
        $this->load->library('upload');
        
        $error = FALSE;
        
        $this->db->trans_begin();
        
        if (is_numeric($id))
        {
            $this->db->update('manufacturers', array('manufacturers_name' => $data['name'], 'last_modified' => date('Y-m-d H:i:s')), array('manufacturers_id' => $id));
        }
        else
        {
            $this->db->insert('manufacturers', array('manufacturers_name' => $data['name'], 'date_added' => date('Y-m-d H:i:s')));
        }
        
        if ($this->db->trans_status() === TRUE)
        {
            $manufacturers_id = $id ? $id : $this->db->insert_id();
            
            $config['upload_path'] = ROOTPATH . 'images/manufacturers/';
            $config['allowed_types'] = 'gif|jpg|png';
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload($data['image']))
            {
                $manufacturers_image_info = $this->upload->data();
                
                $this->db->update('manufacturers', array('manufacturers_image' => $manufacturers_image_info['file_name']), array('manufacturers_id' => $manufacturers_id));
                
                if ($this->db->trans_status() === FALSE)
                {
                    $error = TRUE;
                }
            }
        }
        else
        {
            $error = TRUE;
        }
        
        if ($error === FALSE)
        {
            foreach(lang_get_all() as $l)
            {
                $manufacturers_info = array('manufacturers_url' => $data['url'][$l['id']], 
                                            'manufacturers_friendly_url' => $data['friendly_url'][$l['id']], 
                                            'manufacturers_page_title' => $data['page_title'][$l['id']], 
                                            'manufacturers_meta_keywords' => $data['meta_keywords'][$l['id']], 
                                            'manufacturers_meta_description' => $data['meta_description'][$l['id']]);
                
                if (is_numeric($id))
                {
                    $this->db->update('manufacturers_info', $manufacturers_info, array('manufacturers_id' => $manufacturers_id, 'languages_id' => $l['id']));
                }
                else
                {
                    $manufacturers_info['manufacturers_id'] = $manufacturers_id;
                    $manufacturers_info['languages_id'] = $l['id'];
                    
                    $this->db->insert('manufacturers_info', $manufacturers_info);
                }
                
                if ($this->db->trans_status() === FALSE)
                {
                    $error = TRUE;
                    break;
                }
            }
        }
        
        if ($error === FALSE)
        {
            $this->db->trans_commit();
            
            return TRUE;
        }
        
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the data of the manufacturer
     *
     * @access public
     * @param $id
     * @param $language_id
     * @return mixed
     */
    public function get_data($id, $language_id = null)
    {
        if (empty($language_id))
        {
           $language_id = lang_id();
        }
        
        $result = $this->db
        ->select('m.*, mi.*')
        ->from('manufacturers m')
        ->join('manufacturers_info mi', 'm.manufacturers_id = mi.manufacturers_id')
        ->where(array('m.manufacturers_id' => $id, 'mi.languages_id' => $language_id))
        ->get();
        
        if ($result->num_rows() > 0)
        {
            $data = $result->row_array();
        
            $result->free_result();
            
            $result = $this->db
            ->select_sum('url_clicked', 'total')
            ->from('manufacturers_info')
            ->where('manufacturers_id', $id)
            ->get();
            
            $clicks = $result->row_array();
            $result->free_result();
            
            $data['url_clicks'] = $clicks['total'];
            
            $data['products_count'] = $this->db->where('manufacturers_id', $id)->from('products')->count_all_results();
            
            return $data;
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the info of the manufacturer with id
     *
     * @access public
     * @param $id
     * @return mixed
     */
    public function get_info($id)
    {
        $result = $this->db
        ->select('languages_id, manufacturers_url, manufacturers_friendly_url, manufacturers_page_title, manufacturers_meta_keywords, manufacturers_meta_description')
        ->from('manufacturers_info')
        ->where('manufacturers_id', $id)
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get all the manufacturers
     *
     * @access public
     * @return mixed
     */
    public function get_manufacturers_data()
    {
        $result = $this->db
        ->select('*')
        ->from('manufacturers')
        ->get();
        
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the total number of the manufacturers
     *
     * @access public
     * @return int
     */
    public function get_totals()
    {
        return $this->db->count_all('manufacturers');
    }
}

/* End of file manufacturers_model.php */
/* Location: ./system/models/manufacturers_model.php */