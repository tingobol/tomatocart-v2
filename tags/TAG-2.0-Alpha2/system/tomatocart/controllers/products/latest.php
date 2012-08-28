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
 * Latest Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-products-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Latest extends TOC_Controller
{
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
     */
    public function index()
    {
        //page title
        $this->set_page_title(lang('new_products'));
        
        //breadcrumb
        $this->template->set_breadcrumb(lang('new_products'), site_url('latest'));

        $this->load->model('products_model');

        //get page
        $page = $this->uri->segment(2);
        $filter = array(
            'page' => (isset($page) && is_numeric($page)) ? ($page - 1) : 0,
            'per_page' => config('MAX_DISPLAY_SEARCH_RESULTS'));

        //get new products
        $new_products = $this->products_model->get_new_products($filter);

        //initialize the products data
        $products = array();
        if (sizeof($new_products) > 0)
        {
            foreach ($new_products as $product)
            {
                $products[] = array(
                    'products_id' => $product['products_id'],
                    'product_name' => $product['products_name'],
                    'short_description' => $product['products_short_description'],
                    'product_image' => $product['image'],
                    'product_price' => $product['products_price']);
            }
        }
        $data['products'] = $products;

        //load pagination library
        $this->load->library('pagination');

        //initialize pagination parameters
        $pagination['base_url'] = site_url('latest');
        $pagination['total_rows'] = $this->products_model->count_new_products($filter);
        $pagination['per_page'] = config('MAX_DISPLAY_SEARCH_RESULTS');
        $pagination['use_page_numbers'] = TRUE;
        $pagination['uri_segment'] = 2;

        //initialize pagination
        $this->pagination->initialize($pagination);
        $data['links'] = $this->pagination->create_links();

        $this->template->build('products/latest', $data);
    }
}

/* End of file lasest.php */
/* Location: ./system/tomatocart/controllers/products/lasest.php */