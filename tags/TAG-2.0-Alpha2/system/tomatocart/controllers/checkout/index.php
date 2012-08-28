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
 * Index Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-checkout-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Index extends TOC_Controller {

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

    public function load_form($form)
    {
        switch ($form) {
            case 'login':
                $this->lang->db_load('account');
                $this->template->set_layout(FALSE)->build('checkout/login_form');
                break;
            case 1:
                echo "i equals 1";
                break;
            case 2:
                echo "i equals 2";
                break;
        }
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $data['title'] = 'Shopping Cart';
        $data['shopping_cart_heading_text'] = 'Shopping Cart';
        $data['data_added_text'] = 'Date Added:';
        $data['checkout_text'] = 'Check Out';
        $data['update_text'] = 'Update';
        $data['continue_text'] = 'Continue Shopping';


        $contents = $this->shopping_cart->get_contents();

        $products = array();
        if (sizeof($contents) > 0)
        {
            foreach ($contents as $content)
            {
                $products[] = array(
          'id' => $content['id'],
          'name' => $content['name'],
          'type' => '0',
          'keyword' => null,
          'sku' =>'',
          'image' => $content['image'],
          'price' => '400',
          'final_price' => '400',
          'quantity' => '1',
          'weight' => '10.00',
          'tax_class_id' => '0',
          'date_added' => '07/14/2011',
          'weight_class_id' => '2',
          'link' => site_url('products/' . $content['id']),
          'gc_data' => null

                );
            }
        }
         
        $data['products'] = $products;

        $data['cart_update_link'] = '';
        $data['cart_remove_link'] = '';

        $data['logged_on'] = $this->customer->is_logged_on();

        $this->template->build('checkout/checkout', $data);
    }
}

/* End of file index.php */
/* Location: ./system/tomatocart/controllers/checkout/index.php */