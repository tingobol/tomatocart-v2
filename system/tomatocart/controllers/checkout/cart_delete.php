<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
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
 * Cart_Delete Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-checkout-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Cart_Delete extends TOC_Controller {
    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Default Function
     *
     * @access public
     * @param string
     * @return void
     */
    public function index($id)
    {
        //decode product id string
        $products_id = decode_product_id_string($id);
        
        //if it exists in the shopping cart
        if ($this->shopping_cart->exists($products_id))
        {
            $this->shopping_cart->remove($products_id);
        }

        redirect('checkout/shopping_cart');
    }
}

/* End of file cart_delete.php */
/* Location: ./system/tomatocart/controllers/checkout/cart_delete.php */