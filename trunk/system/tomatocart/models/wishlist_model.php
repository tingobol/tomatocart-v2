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
 * Wishlist_Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-departments-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Wishlist_Model extends CI_Model
{

    const TB_WISHLIST='toc_wishlists';
    const TB_WISHLIST_PRODUCT = 'toc_wishlists_products';
    
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
     * insert a new wishlist for a customers
     * 
     * @access public
     * @param int $customers_id
     * @param string $wishlists_token
     * @return
     */
    public function insert_wishlist($customers_id,$wishlists_token)
    {
        $this->db->set('customers_id',$customers_id);
        $this->db->set('wishlists_token',$wishlists_token);
        $this->db->insert('toc_wishlists');
        
        return ($this->db->affected_rows() ==1) ? $this->db->insert_id() : FALSE;
    }
    
    /**
     * get wishlist by id
     * @param int $wishlist_id
     */
    public function get_wishlist_by_id($wishlist_id)
    {
        $this->db->where('wishlists_id', $wishlist_id);
        $result = $this->db->get('toc_wishlists');
        if($result->num_rows() > 0){
            return $result->first_row('array');
        }else{
            return FALSE;
        }
    }
    
    /**
     * get wishlist by customer id
     * @param unknown_type $customers_id
     * @return boolean
     */
    public function get_wishlist_by_cid($customers_id)
    {
        $this->db->where('customers_id', $customers_id);
        $result = $this->db->get('toc_wishlists');
        if($result->num_rows() > 0){
            return $result->first_row('array');
        }else{
            return FALSE;
        }
    }
    
    /**
     * get wishlist by customer token
     * @param string $token
     */
    public function get_wishlist_by_token($token)
    {
        $this->db->where('wishlists_token', $token);
        $result = $this->db->get('toc_wishlists');
        if($result->num_rows() > 0){
            return $result->first_row('array');
        }else{
            return FALSE;
        }
    }
    /**
     * if a user is a new register, then update the wishlist customer_id in db
     * @param unknown_type $wishlist_id
     * @param unknown_type $customers_id
     */
    public function update_wishlist_customer($wishlist_id,$customers_id)
    {
        $this->db->set('customers_id',$customers_id);
        $this->db->where('wishlists_id',$wishlist_id);
        $this->db->update('toc_wishlists');
        return ($this->db->affected_rows() ==1) ? TRUE : FALSE;        
    }
    
    /**
     * add a product to a wishlist
     * @param int $wishlists_id
     * @param int $products_id
     * @param string $comments
     */
    public function add_product_to_wishlist($wishlists_id,$products_id,$comments=NULL)
    {
        $this->db->set('wishlists_id',$wishlists_id);
        $this->db->set('products_id',$products_id);
        $this->db->set('date_added','now()');
        if($comments){
            $this->db->set('comments',$comments);
        }
        $this->db->insert('toc_wishlists'_PRODUCT);
        return ($this->db->affected_rows() ==1) ? $this->db->insert_id() : FALSE;
    }
    
    /**
     * get all products in the wishlist
     * @param int $wishlist_id
     * @return boolean
     */
    public function get_wishlist_all_products($wishlist_id)
    {
        $this->db->where('wishlists_id',$wishlist_id);
        $result = $this->db->get('toc_wishlists'_PRODUCT);
        if($result->num_rows() > 0){
            return $result->row_array();
        }else{
            return FALSE;
        }
    }
    
    /**
     * update a wishlist product comment by id
     * @param int $wishlist_product_id
     * @param string $comment
     */
    public function update_comment($wishlist_product_id,$comment)
    {
        $this->db->set('comments',$comments);
        $this->db->where('wishlists_products_id',$wishlist_product_id);
        $this->db->update('toc_wishlists'_PRODUCT);
        return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
    }
    
    public function update_wishlist_belong($wishlists_id_old,$wishlist_id_new){
        $this->db->set('wishlists_id',$wishlist_id_new);
        $this->db->where('wishlists_id',$wishlists_id_old);
        $this->db->update('toc_wishlists'_PRODUCT);
        return ($this->db->affected_rows() >0) ? TRUE : FALSE;        
    }
    
    public function get_wishlist_product_by_id($wishlists_products_id)
    {
        $this->db->where('wishlists_products_id',$wishlists_products_id);
        $result = $this->db->get('toc_wishlists'_PRODUCT);
        if($result->num_rows() > 0){
            return $result->first_row('array');
        }else{
            return FALSE;
        }
    }
    
    public function get_wishlist_product_by_pid($wishlists_id,$products_id)
    {
        $this->db->where('wishlists_id',$wishlists_id);
        $this->db->where('products_id',$products_id);
        $result = $this->db->get('toc_wishlists'_PRODUCT);
        if($result->num_rows() > 0){
            return $result->first_row('array');
        }else{
            return FALSE;
        }
    }
    
    
    public function delete_product($wishlists_id,$products_id=NULL)
    {
        $this->db->where('wishlists_id',$wishlists_id);
        if($products_id){
            $this->db->where('products_id',$products_id);
        }
        $this->db->delete('toc_wishlists'_PRODUCT);
        return ($this->db->affected_rows() >0) ? TRUE : FALSE;
    }
    
    public function delete_product_by_id($wishlist_product_id)
    {
        $this->db->where('wishlists_products_id',$wishlist_product_id);
        $this->db->delete('toc_wishlists'_PRODUCT);
        return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
    }
    
    public function delete_wishlist($wishlists_id)
    {
        $this->db->where('wishlists_id',$wishlists_id);
        $this->db->delete('toc_wishlists');
        return ($this->db->affected_rows() >0) ? TRUE : FALSE;
    }
    
}

/* End of file wishlist_model.php */
/* Location: ./system/tomatocart/models/wishlist_model.php */