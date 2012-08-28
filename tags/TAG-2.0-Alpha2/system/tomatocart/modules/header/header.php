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
 * Module Header Controller
 *
 * Rend the content for the header module.
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Header extends TOC_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    protected $code = 'header';

    /**
     * Header Module Constructor: Initialize Module Title
     *
     * @access public
     * @param string
     */
    public function __construct($config)
    {
        parent::__construct();

        $this->title = 'header';
    }

    /**
     * Default Function 
     *
     * @access public
     * @return string contains the html content of header module
     */
    public function index()
    {
        //initialize the data array
        $data = array();
        
        //customer is logged on
        $data['is_logged_on'] = $this->ci->customer->is_logged_on();
        //shopping cart items
        $data['items_num'] = $this->ci->shopping_cart->number_of_items();
        //breadcrumbs
        $data['breadcrumbs'] = $this->ci->template->get_breadcrumbs();

        //languages
        $data['languages'] = array();

        $languages = $this->ci->lang->get_languages();
        foreach($languages as $language)
        {
            $code = strtolower(substr($language['code'], 3));

            $data['languages'][] = array(
            	'name' => $language['name'], 
            	'url' => current_url() . '?language=' . $language['code'], 
            	'image' => image_url('worldflags/' . $code . '.png'));
        }

        //render view
        return $this->load_view('index.php', $data);
    }
}

/* End of file header.php */
/* Location: ./system/tomatocart/modules/header/header.php */