<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Search Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-search-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Search extends TOC_Controller {
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Default Function
     *
     * @access public
     */
    public function index()
    {
        //set page title
        $this->template->set_title(lang('search_heading'));
        
        //setup view
        $this->template->build('search/search', $this->get_view_data());
    }
    
    /**
     * get the view data
     *
     * @access public
     * @return array
     */
    
    private function get_view_data()
    {
        //load library
        $this->load->library('category_tree');
        $this->category_tree->set_spacer_string('&nbsp;', 2);
        
        //load model
        $this->load->model('manufacturers_model');
        
        //add the categories
        $data['categories'] = array(array('id' => '', 'text' => lang('filter_all_categories')));
        
        foreach($this->category_tree->build_branch_array(0) as $category)
        {
            if (strpos($category['id'], '_') !== FALSE)
            {
                $cPath_array = array_unique(array_filter(explode('_', $category['id']), 'is_numeric'));
                $category_id = end($cPath_array);
            }
            else
            {
                $category_id = $category['id'];
            }
        
        
            $data['categories'][] = array('id' => $category_id, 'text' => $category['title']);
        }
        
        //add the manufacturers
        $data['manufacturers'] = array(array('id' => '', 'text' => lang('filter_all_manufacturers')));
        
        foreach($this->manufacturers_model->get_manufacturers() as $manufacturer)
        {
            $data['manufacturers'][] = array('id' => $manufacturer['manufacturers_id'], 'text' => $manufacturer['manufacturers_name']);
        }
        
        return $data;
    }
    
    /**
     * search with the conditions in the post request
     *
     * @access public
     */
    public function search_with_post()
    {
        //load library
        $this->load->library('search');
        
        //validate the price to search from
        if (!empty($this->input->post('pfrom')))
        {
            if (settype($this->input->post('pfrom'), 'double'))
            {
                $this->search->price_from = $this->input->post('pfrom');
            }
            else
            {
                $this->message_stack->add('search', lang('error_search_price_from_not_numeric'));
            }
        }
        
        //validate the max price to search
        if (!empty($this->input->post('pto')))
        {
            if (settype($this->input->post('pto'), 'double'))
            {
                $this->search->price_to = $this->input->post('pto');
            }
            else
            {
                $this->message_stack->add('search', lang('error_search_price_to_not_numeric'));
            }
        }
        
        //validate the price range
        if (!empty($this->search->price_from) && !empty($this->search->price_to) && ($this->search->price_from >= $this->search->price_to))
        {
            $this->message_stack->add('search', lang('error_search_price_to_less_than_price_from'));
        }
        
        //validate the search keywords
        if (!empty($this->input->post('keywords')) && is_string($this->input->post('keywords')))
        {
            $this->search->set_keywords(urldecode($this->input->post('keywords')));
            
            if (empty($this->search->keywords))
            {
                $this->message_stack->add('search', lang('error_search_invalid_keywords'));
            }
        }
        
        //must enter a search condition
        if (empty($this->search->keywords) && empty($this->search->price_from) && empty($this->search->price_to))
        {
            $this->message_stack->add('search', lang('error_search_at_least_one_input'));
        }
        
        //set the category
        if (!empty($this->input->post('cPath')) && is_numeric($this->input->post('cPath')) && ($this->input->post('cPath') > 0))
        {
            $this->search->category = $this->input->post('cPath');
            
            if (!empty($this->input->post('recursive')))
            {
                $this->search->recursive = $this->input->post('recursive') == '1' ? TRUE : FALSE;
            }
        }
        
        //set the manufacturer
        if (!empty($this->input->post('manufacturers')) && is_numeric($this->input->post('manufacturers')) && ($this->input->post('manufacturers') > 0))
        {
            $this->search->manufacturer = $this->input->post('manufacturers');
        }
        
        //validate faily
        if ($this->message_stack->size('search') > 0)
        {
            //setup view
            $this->template->build('search/search', $this->get_view_data());
        }
        else
        {
            //set the view data
            $data['products'] = $this->search->get_search_results();
            
            //setup view
            $this->template->build('search/results', $data);
        }
    }
    
    /**
     * search with the conditions in the get request
     *
     * @access public
     */
    public function search_with_get()
    {
        //load library
        $this->load->library('search');
        
        //get the url segments
        $segments = $this->uri->uri_to_assoc(2);
        
        //validate the price to search from
        if (isset($segments['pfrom']) && is_numeric($segments['pfrom']) && ($segments['pfrom'] > 0))
        {
            if (settype($segments['pfrom'], 'double'))
            {
                $this->search->price_from = $segments['pfrom'];
            }
            else
            {
                $this->message_stack->add('search', lang('error_search_price_from_not_numeric'));
            }
        }
        
        //validate the max price to search
        if (isset($segments['pto']) && is_numeric($segments['pfrom']) && ($segments['pto'] > 0))
        {
            if (settype($segments['pto'], 'double'))
            {
                $this->search->price_to = $segments['pto'];
            }
            else
            {
                $this->message_stack->add('search', lang('error_search_price_to_not_numeric'));
            }
        }
        
        //validate the price range
        if (!empty($this->search->price_from) && !empty($this->search->price_to) && ($this->search->price_from >= $this->search->price_to))
        {
            $this->message_stack->add('search', lang('error_search_price_to_less_than_price_from'));
        }
        
        //validate manufactuers
        if (isset($segments['manufacturers']) && is_numeric($segments['manufacturers']) && ($segments['manufacturers'] > 0))
        {
            $this->search->manufacturer = $segments['manufacturers'];
        }
        
        //validate faily
        if ($this->message_stack->size('search') > 0)
        {
            //setup view
            $this->template->build('search/search', $this->get_view_data());
        }
        else
        {
            //set the view data
            $data['products'] = $this->search->get_search_results();
            
            //setup view
            $this->template->build('search/results', $data);
        }
    }
}

/* End of file search.php */
/* Location: ./system/tomatocart/controllers/search/search.php */