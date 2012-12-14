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
 * Departments_Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-departments-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Shopping_Cart_Model extends CI_Model
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

    /**
     * Get shopping cart content
     * 
     * @access public
     * @param $customers_id
     * @return mixed
     */
    public function get_contents($customers_id)
    {
        $result = $this->db->select('products_id, customers_basket_quantity')->from('customers_basket')->where('customers_id', $customers_id)->get();

        $contents = FALSE;
        if ($qry->num_rows() > 0)
        {
            foreach ($result->result_array() as $row)
            {
                $contents['products_id'] = $row['customers_basket_quantity'];
            }
        }

        return $contents;
    }

    /**
     * Update shopping cart content
     * 
     * @access public
     * @param $customers_id
     * @param $products_id
     * @param $quantity
     * @return boolean
     */
    public function update_content($customers_id, $products_id, $quantity)
    {
        $this->db->set('customers_basket_quantity', $quantity);
        $this->db->where('customers_id', $customers_id);
        $this->db->where('products_id', $products_id);
        return $this->db->update('customers_basket');
    }

    /**
     * Insert content
     * 
     * @access public
     * @param $customers_id
     * @param $products_id
     * @param $quantity
     * @return boolean
     */
    public function insert_content($customers_id, $products_id, $quantity)
    {
        $this->db->set('customers_basket_quantity', $quantity);
        $this->db->set('customers_id', $customers_id);
        $this->db->set('products_id', $products_id);
        return $this->db->insert('customers_basket');
    }

    /**
     * Delete shopping cart content
     * 
     * @access public
     * @param $products_id
     * @return boolean
     */
    public function delete_content($products_id)
    {
        return $this->db->where('products_id', $products_id)->delete('customers_basket');
    }

    /**
     * Delete complete content
     * 
     * @access public
     * @param $customers_id
     * @return boolean
     */
    public function delete($customers_id)
    {
        return $this->db->where('customers_id', $customers_id)->delete('customers_basket');
    }
}

/* End of file shopping_cart_model.php */
/* Location: ./system/tomatocart/models/shopping_cart_model.php */