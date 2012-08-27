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

class Wishlist_Model extends CI_Model
{

    const TB_WISHLIST='toc_wishlists';
    const TB_WISHLIST_PRODUCT = 'toc_wishlists_products';

    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * insert a new wishlist for a customers
     * @param int $customers_id
     * @param string $wishlists_token
     */
    public function insert_wishlist($customers_id,$wishlists_token)
    {
        $this->db->set('customers_id',$customers_id);
        $this->db->set('wishlists_token',$wishlists_token);
        $this->db->insert(self::TB_WISHLIST);
        return ($this->db->affected_rows() ==1) ? $this->db->insert_id() : FALSE;
    }
    
    /**
     * get wishlist by id
     * @param int $wishlist_id
     */
    public function get_wishlist_by_id($wishlist_id)
    {
        $this->db->where('wishlists_id', $wishlist_id);
        $result = $this->db->get(self::TB_WISHLIST);
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
        $result = $this->db->get(self::TB_WISHLIST);
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
        $result = $this->db->get(self::TB_WISHLIST);
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
        $this->db->update(self::TB_WISHLIST);
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
        $this->db->insert(self::TB_WISHLIST_PRODUCT);
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
        $result = $this->db->get(self::TB_WISHLIST_PRODUCT);
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
        $this->db->update(self::TB_WISHLIST_PRODUCT);
        return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
    }
    
    public function update_wishlist_belong($wishlists_id_old,$wishlist_id_new){
        $this->db->set('wishlists_id',$wishlist_id_new);
        $this->db->where('wishlists_id',$wishlists_id_old);
        $this->db->update(self::TB_WISHLIST_PRODUCT);
        return ($this->db->affected_rows() >0) ? TRUE : FALSE;        
    }
    
    public function get_wishlist_product_by_id($wishlists_products_id)
    {
        $this->db->where('wishlists_products_id',$wishlists_products_id);
        $result = $this->db->get(self::TB_WISHLIST_PRODUCT);
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
        $result = $this->db->get(self::TB_WISHLIST_PRODUCT);
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
        $this->db->delete(self::TB_WISHLIST_PRODUCT);
        return ($this->db->affected_rows() >0) ? TRUE : FALSE;
    }
    
    public function delete_product_by_id($wishlist_product_id)
    {
        $this->db->where('wishlists_products_id',$wishlist_product_id);
        $this->db->delete(self::TB_WISHLIST_PRODUCT);
        return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
    }
    
    public function delete_wishlist($wishlists_id)
    {
        $this->db->where('wishlists_id',$wishlists_id);
        $this->db->delete(self::TB_WISHLIST);
        return ($this->db->affected_rows() >0) ? TRUE : FALSE;
    }
    
}

/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */