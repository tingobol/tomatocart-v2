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
 * Frontend Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Controller extends CI_Controller
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
        
        //initialize system language
        $this->lang->initialize();
        $this->output->set_header('Content-Type: text/html; charset=' . $this->lang->get_character_set());
        setlocale(LC_TIME, explode(',', $this->lang->get_locale()));

        $this->lang->db_load('general');
        $this->lang->db_load('modules-boxes');
        $this->lang->db_load('modules-content');
        
        //load cache
        $this->load->driver('cache', array('adapter' => 'file'));

        //load category tree
        $this->load->library('category_tree');

        //load settings
        $this->settings = $this->settings_model->get_settings();

        //load language package
        if ($this->uri->rsegment(1) !== FALSE)
        {
            $this->lang->db_load($this->uri->segment(1));
        }

        /**initialize module specific data**/
        $module = trim($this->router->directory, '/');
        $class = $this->router->class;

        //load language data
        $this->lang->db_load($module);

        //load template
        $this->load->library('template');
        $this->load->model('modules_model');

        $medium = $this->agent->get_medium();

        //load modules according to the module and page
        $this->modules = $this->modules_model->get_modules($module, $class, $medium);
        $this->template->add_modules($this->modules);
        
        //set breadcrumb
        $this->template->set_breadcrumb(lang('home'), site_url());
        
        //add common data
        $this->template->set('is_logged_on', $this->customer->is_logged_on());
        $this->template->set('items_num', $this->shopping_cart->number_of_items());

        //languages
        $langs = array();

        $languages = $this->lang->get_languages();
        foreach($languages as $language)
        {
            $code = strtolower(substr($language['code'], 3));

            $langs[] = array(
            	'name' => $language['name'], 
            	'url' => current_url() . '?language=' . $language['code'], 
            	'image' => image_url('worldflags/' . $code . '.png'));
        }
        $this->template->set('languages', $langs);
        
        //set layout
        $this->template->set_layout('index.php');
    }
    
    /**
     * Set the page title
     * 
     * @param $title page titme
     */
    protected function set_page_title($title) 
    {
        $this->template->set_title($title . ' -- ' . config('STORE_NAME'));
    }
}

/* End of file TOC_Controller.php */
/* Location: ./system/tomatocart/core/TOC_Controller.php */