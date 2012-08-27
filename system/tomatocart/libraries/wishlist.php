<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source wishlist Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Wishlist  Class
 *
 * The wishlist class is copied from TomatoCart v1.0. It keep some basic features and following features are
 * removed:
 *   gift wrapping
 *   gift wrapping message
 *   coupon
 *   gift certificate
 *   customer credit
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class TOC_Wishlist {
    private $ci = null;
    private $contents = array();
    private $wishlists_id = null;
    private $customer_id=NULL;
    private $token = null;
    private $dirty = FALSE;
    const WISHLIST_SESSION = 'wishlist_session';

    function __construct()
    {
        parent::__construct();
        $this->ci =& get_instance();
        $this->ci->load->model('wishlist_model');
        if ($this->ci->customer->is_logged_on())
        {
            $this->customer_id = $this->ci->customer->get_id();
        }
        if($this->ci->session->userdata(self::WISHLIST_SESSION) !== FALSE)
        {
            $sdata = $this->ci->session->userdata(self::WISHLIST_SESSION);
            $this->contents = $sdata['contents'];
            $this->wishlists_id = $sdata['wishlists_id'];
            $this->token = $sdata['token'];
        }else
        {
            $hs_db = $this->load_wishlist_form_db();
            if($hs_db)
            {
                $this->synchronize_to_session(TRUE);
            }
        }

    }
    
    public function get_products(){
        $products = array();
        if(count($this->contents)>0){
            $this->ci->load->model('products_model');
            foreach ($this->contents as $pid=>$wp){
                if(!isset($wp['name'])){
                    $db_pdt = $this->ci->products_model->get_product_data($pid);
                    if($db_pdt){
                        $wp['name'] = $db_pdt['products_name'];
                        $wp['image'] = $db_pdt['image'];
                        $wp['price'] = $db_pdt['products_price'];
                        $this->contents[$pid]['name'] = $wp['name'];
                        $this->contents[$pid]['image'] = $wp['image'];
                        $this->contents[$pid]['price'] = $wp['price'];
                        $this->dirty = TRUE;
                    }
                }
                $product['id'] = $pid;
                $product['name'] = $wp['name'];
                $product['image'] = $wp['image'];
                $product['price'] = $wp['price'];
                $product['comments'] = $wp['comments'];
                $product['date_added'] = date_format($wp['date_added'],'m/d/y');
                array_push($products, $product);
            }
            $this->synchronize_to_session();
        }
        return $products;
    }

    private function load_wishlist_form_db()
    {
        if($this->customer_id)
        {
            $db_wish = $this->ci->wishlist_model->get_wishlist_by_cid($this->customer_id);
            if($db_wish)
            {
                $this->token = $db_wish['wishlists_token'];
                $this->wishlists_id = $db_wish['wishlists_id'];
                $products = $this->ci->wishlist_model->get_wishlist_all_products($this->wishlists_id);
                if($products){
                    foreach ($products as $p)
                    {
                        $this->contents[$p['products_id']] = $p;
                    }
                }
                return TRUE;
            }
        }
        return FALSE;
    }

    private function synchronize_to_session($force=FALSE)
    {
        if(!$this->dirty&&!$force)
        {
            return FALSE;
        }
        $wishcache = array();
        $wishcache['contents'] = $this->contents;
        $wishcache['wishlists_id'] = $this->wishlists_id;
        $wishcache['token'] = $this->token;
        $this->ci->session->set_userdata(self::WISHLIST_SESSION,$wishcache);
        $this->dirty = FALSE;
    }
     
    public function get_token()
    {
        return $this->token;
    }
    
    /**
     * clear wishlist
     * @param unknown_type $db_force
     */
    public function reset($db_force = FALSE)
    {
        $this->wishlists_id = null;
        $this->token = null;
        $this->contents = array();
        if($db_force)
        {
            $this->ci->wishlist_model->delete_product($this->wishlists_id);
        }
    }

    /**
     * add a product to wishlist
     * @param int $product_id
     * @param string $comments
     */
    public function add_product($product_id,$comments=NULL)
    {
        if(!$this->wishlists_id)
        {
            $token = NULL;
            if($this->customer_id)
            {
                $token = md5($this->customer_id.time());
            }
            else
            {
                $token = md5($this->ci->session->userdata('session_id').time());
            }
            
            $wid = $this->ci->wishlist_model->insert_wishlist($this->customer_id,$token);
            if($wid)
            {
                $this->wishlists_id = $wid;
                $this->token = $token;
            }
        }
        $product = FALSE;
        if($this->wishlists_id)
        {
            $db_product = $this->ci->wishlist_model->get_wishlist_product_by_pid($this->wishlists_id,$product_id);
            if($db_product)
            {
                return FALSE;
            }
            $pid = $this->ci->wishlist_model->add_product_to_wishlist($this->wishlists_id,$product_id,$comments);
            if($pid)
            {
                $product = $this->ci->wishlist_model->get_wishlist_product_by_id($pid);
                $this->contents[pid] = $product;
                $this->dirty = TRUE;
            }
        }
        
        $this->synchronize_to_session();
        
        return $product;
    }
    
    /**
     * remove procuct
     * @param int $product_id
     */
    public function remove_product($product_id)
    {
        $is_del = $this->ci->wishlist_model->delete_product($this->wishlists_id,$product_id);
        if($is_del){
            unset($this->contents[$product_id]);
            $this->dirty = TRUE;
            $this->synchronize_to_session();
        }
    }
    
    /**
     * when the user has a wishlist when they doesn't logon,
     * if they are logging on , the wishlist in the session will merge to the customer wishlist,
     * if the user is a new register, then update customer_id in db
     * @return boolean
     */
    public function update_and_merge_wishlist()
    {
        if(!$this->wishlists_id){
            return FALSE;
        }
        
        if($this->ci->customer->is_logged_on()){
            $this->customer_id = $this->ci->customer->get_id();
            $db_wishlist = $this->ci->wishlist_model->get_wishlist_by_cid($this->customer_id);
            if($db_wishlist){// exsit products wishlist
                $this->ci->wishlist_model->update_wishlist_belong($this->wishlists_id,$db_wishlist['wishlists_id']);
                $this->ci->wishlist_model->delete_wishlist($this->wishlists_id);
                $this->wishlists_id = $db_wishlist['wishlists_id'];
                
                $this->reset();
                $this->load_wishlist_form_db();
                $this->synchronize_to_session(TRUE);
            }else{
                $this->ci->wishlist_model->update_wishlist_customer($this->wishlists_id,$this->customer_id);
            }
        }
    }

}