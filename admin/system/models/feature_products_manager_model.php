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
 * Feature Products Manager Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Feature_Products_Manager_Model extends CI_Model
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
     * Get the products which will be displayed in the feature products module
     *
     * @access public
     * @param $start
     * @param $limit
     * @param $in_categories
     * @return mixed
     */
    public function get_products($start, $limit, $in_categories = array())
    {
        $this->query($in_categories);
      
        $result = $this->db
        ->order_by('pf.sort_order')
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
     * Delete the product
     *
     * @access public
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->delete('products_frontpage', array('products_id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Update the sort order of the feature product
     *
     * @access public
     * @param $id
     * @param $value
     * @return boolean
     */
    public function save($id, $value)
    {
        $this->db->update('products_frontpage', array('sort_order' => $value), array('products_id' => $id));
        
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get total number of the feature products
     *
     * @access public
     * @param $in_categories
     * @return int
     */
    public function get_total($in_categories)
    {
        $this->query($in_categories);
        
        $result = $this->db->get();
        
        return $result->num_rows();
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get total number of the feature products
     *
     * @access private
     * @param $in_categories
     * @return void
     */
    private function query($in_categories)
    {
        $this->db
        ->select('pd.products_id, pd.products_name, pf.sort_order')
        ->from('products_frontpage pf')
        ->join('products_description pd', 'pf.products_id = pd.products_id');
        
        if (!empty($in_categories))
        {
            $this->db
            ->join('products_to_categories p2c', 'p2c.products_id = pf.products_id')
            ->where_in('p2c.categories_id', $in_categories);
        }
        
        $this->db->where('pd.language_id', lang_id());
    }
}

/* End of file feature_products_manager_model.php */
/* Location: ./system/models/feature_products_manager_model.php */