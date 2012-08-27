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
 * Cart_Add Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-checkout-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Cart_Add extends TOC_Controller {
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
        if (is_numeric($id))
        {
            $this->load->library('product', $id, 'product_' . $id);
            $product = $this->{'product_' . $id};

            //if product is found
            if ($product->is_valid())
            {
                //variants
                $variants = ($this->input->post('variants') === FALSE) ? null : $this->input->post('variants');

                //quantity
                $quantity = ($this->input->post('quantity') === FALSE) ? null : $this->input->post('quantity');

                //add to shopping cart
                $this->shopping_cart->add($id, $variants, $quantity);
            }
        }


        redirect('checkout/shopping_cart');
    }
}

/* End of file cart_add.php */
/* Location: ./system/tomatocart/controllers/checkout/cart_add.php */