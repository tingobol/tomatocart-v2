<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package      TomatoCart
 * @author       TomatoCart Dev Team
 * @copyright    Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html
 * @link         http://tomatocart.com
 * @since        Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Feature Products Controller
 *
 * @package     TomatoCart
 * @subpackage  tomatocart
 * @category    template-module-controller
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */
class Feature_Products extends TOC_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    protected $code = 'feature_products';

    /**
     * Template Module Author Name
     *
     * @access private
     * @var string
     */
    protected $author_name = 'TomatoCart';

    /**
     * Template Module Author Url
     *
     * @access private
     * @var string
     */
    protected $author_url = 'http://www.tomatocart.com';

    /**
     * Template Module Version
     *
     * @access private
     * @var string
     */
    protected $version = '1.0';

    /**
     * Template Module Parameters
     *
     * @access private
     * @var string
     */
    protected $params = array(
        array('name' => 'MODULE_FEATURE_PRODUCTS_MAX_DISPLAY',
              'title' => 'Maximum Entries To Display', 
              'type' => 'numberfield',
    		  'value' => '9',
              'description' => 'Maximum number of feature products to display'));

    /**
     * Feature Products Constructor
     *
     * @access public
     */
    public function __construct($config)
    {
        parent::__construct();

        if (!empty($config) && is_string($config))
        {
            $this->config = json_decode($config, TRUE);
        }

        $this->title = lang('feature_products_title');
    }

    /**
     * Default Function
     *
     * @access public
     * @return string contains the html content of article categories module
     */
    public function index()
    {
        $this->ci->load->model('products_model');

        $products = $this->ci->products_model->get_feature_products();
        if ($products != NULL)
        {
            $data = array();
            foreach($products as $product) 
            {
                $data['products'][] = array(
                'products_id' => $product['products_id'],
                'products_name' => $product['products_name'],
              	'products_image' => $product['image'],
              	'products_price' => $product['products_price']);
            }
             
            return $this->load_view('index.php', $data);
        }

        return NULL;
    }
}

/* End of file feature_products.php */
/* Location: ./system/tomatocart/modules/feature_products/feature_products.php */