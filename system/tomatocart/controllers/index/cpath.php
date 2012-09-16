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
 * Cpath Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-index-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Cpath extends TOC_Controller
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
        //get function arguments
        $arguments = func_get_args();

        //cnnect arguments to cpath
        $cpath = implode('/', $arguments);

        //if has arguments
        if ($cpath != NULL)
        {
            //compile cpath from friendly url
            $cpath = $this->category_tree->parse_cpath($cpath);

            //page title
            $this->template->set_title(sprintf(lang('index_heading'), config('STORE_NAME')));

            //check whether cpath is exist
            if (isset($cpath) && !empty($cpath))
            {
                //get current category id
                $categories = explode('_', $cpath);
                $current_category_id = end($categories);

                //set global variable
                $this->registry->set('cpath', $cpath);
                $this->registry->set('current_category_id', $current_category_id);

                //model
                $this->load->model('categories_model');
                $this->load->model('products_model');

                //breadcrumb
                $categories = $this->category_tree->get_full_cpath_info($cpath);
                foreach ($categories as $categories_id => $categories_name)
                {
                    $this->template->set_breadcrumb($categories_name, site_url('cpath/' . $categories_id));
                }

                //load the category object
                $this->load->library('category', $current_category_id);

                //set page title
                $data['title'] = $this->category->get_title();
                $this->set_page_title($this->category->get_title());

                //set page keywords
                $meta_keywords = $this->category->get_meta_keywords();
                if (!empty($meta_keywords)) {
                    $this->template->add_meta_tags('keywords', $meta_keywords);
                }

                //set meta description
                $meta_description = $this->category->get_meta_description();
                if (!empty($meta_description))
                {
                    $this->template->add_meta_tags('description', $meta_description);
                }

                //check whether this category has products
                if ($this->categories_model->has_products($current_category_id))
                {
                    //get page
                    $page = $this->uri->segment(4);
                    $filter = array(
                    'categories_id' => $current_category_id,
                    'page' => (isset($page) && is_numeric($page)) ? ($page - 1) : 0,
                    'per_page' => config('MAX_DISPLAY_SEARCH_RESULTS'));

                    $products = $this->products_model->get_products($filter);

                    //initialize pagination parameters
                    $pagination['base_url'] = site_url('cpath/' . $cpath . '/page');
                    $pagination['total_rows'] = $this->products_model->count_products($filter);
                    $pagination['per_page'] = config('MAX_DISPLAY_SEARCH_RESULTS');
                    $pagination['use_page_numbers'] = TRUE;
                    $pagination['uri_segment'] = 4;

                    //load pagination library
                    $this->load->library('pagination');
                    $this->pagination->initialize($pagination);
                    $data['links'] = $this->pagination->create_links();

                    $data['products'] = array();
                    foreach ($products as $product)
                    {
                        $data['products'][] = array(
                      'products_id' => $product['products_id'],
                      'product_name' => $product['products_name'],
                      'product_price' => $product['products_price'],
                      'product_image' => $product['image'],
                      'short_description' => $product['products_short_description']);
                    }

                    $this->template->build('index/product_listing', $data);
                }
                else
                {
                    $children = array();

                    $data['categories'] = $this->category_tree->get_children($current_category_id, $children);

                    $this->template->build('index/category_listing', $data);
                }
            }
        }
        else
        {
            redirect('index');
        }
    }
}

/* End of file cpath.php */
/* Location: ./system/tomatocart/controllers/index/cpath.php */