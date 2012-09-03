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

        $filters = $this->get_search_filters();

        //print_r($filters);
        //print_r($this->message_stack->output('search'));
        //exit;

        if (($filters != NULL) && ($this->message_stack->size('search') > 0))
        {
            $this->load_advanced_search_form();
        }
        else
        {
            //load model
            $this->load->model('search_model');

            $data['products'] = $this->search_model->get_result($filters);

            //setup view
            $this->template->build('search/results', $data);
        }
    }

    /**
     * Get filters
     *
     * @access private
     */
    private function get_search_filters()
    {
        $filters = array();

        //keywords
        $keywords = $this->get_search_filter('keywords');
        if ($keywords != NULL)
        {
            $filters['keywords'] = $keywords;
        }

        //pfrom
        $pfrom = $this->get_search_filter('pfrom');
        if ($pfrom != NULL)
        {
            if (!is_numeric($pfrom))
            {
                $this->message_stack->add('search', lang('error_search_price_from_not_numeric'));
            }
            else
            {
                $filters['price_from'] = $pfrom;
            }
        }

        //pto
        $pto = $this->get_search_filter('pto');
        if ($pto != NULL)
        {
            if (!is_numeric($pto))
            {
                $this->message_stack->add('search', lang('error_search_price_to_not_numeric'));
            }
            else
            {
                $filters['price_to'] = $pto;
            }
        }

        //validate the price range
        if (($pfrom != NULL) && ($pto != NULL) && ($pfrom >= $pto))
        {
            $this->message_stack->add('search', lang('error_search_price_to_less_than_price_from'));
        }

        //cpath
        $cpath = $this->get_search_filter('cPath');
        if ($cpath != NULL)
        {
            $filters['category'] = $cpath;

            $recursive = $this->get_search_filter('recursive');
            if ($recursive != NULL)
            {
                $filters['recursive'] = $recursive == '1' ? TRUE : FALSE;
            }

            if (!empty($cpath) && ($recursive === TRUE))
            {
                $subcategories = array();

                //add the subcategories
                $this->ci->category_tree->get_children($cpath, $subcategories);

                if (!empty($subcategories))
                {
                    $filters['subcategories'] = array($cpath);

                    foreach($subcategories as $subcategory)
                    {
                        if (strpos($subcategory['id'], '_') !== FALSE)
                        {
                            $cPath_array = array_unique(array_filter(explode('_', $subcategory['id']), 'is_numeric'));
                            $category_id = end($cPath_array);

                            $filters['subcategories'][] = $category_id;
                        }
                        else
                        {
                            $filters['subcategories'][] = $subcategory['id'];
                        }
                    }
                }
            }
        }

        //manufacturers
        $manufacturers = $this->get_search_filter('manufacturers');
        if (($manufacturers != NULL) && is_numeric($manufacturers) && ($manufacturers > 0))
        {
            $filters['manufacturer'] = $manufacturers;
        }

        if (is_array($filters) && !empty($filters))
        {
            return $filters;
        }
        
            //must enter a search condition
        if (($pfrom == NULL) && ($pto == NULL) && ($keywords == NULL) && ($manufacturers == NULL))
        {
            $this->message_stack->add('search', lang('error_search_at_least_one_input'));
        }

        return NULL;
    }

    /**
     * Get filter
     *
     * @access private
     * @return mix
     */
    private function get_search_filter($key)
    {
        $value = $this->input->get($key);
        if ($value == NULL)
        {
            $value = $this->input->post($key);
        }

        return $value;
    }

    /**
     * Load advanced search form
     *
     * @access public
     */
    public function load_advanced_search_form()
    {
        //add the categories
        $data['categories'][''] = lang('filter_all_categories');

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

            $data['categories'][$category_id] = $category['title'];
        }

        //load model
        $this->load->model('manufacturers_model');

        //add the manufacturers
        $data['manufacturers'] = array(array('id' => '', 'text' => lang('filter_all_manufacturers')));

        foreach($this->manufacturers_model->get_manufacturers() as $manufacturer)
        {
            $data['manufacturers'][$manufacturer['manufacturers_id']] = $manufacturer['manufacturers_name'];
        }

        //setup view
        $this->template->build('search/advanced_search', $data);
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
        $pfrom = $this->input->post('pfrom');
        if (!empty($pfrom))
        {
            if (settype($pfrom, 'double'))
            {
                $this->search->price_from = $pfrom;
            }
            else
            {
                $this->message_stack->add('search', lang('error_search_price_from_not_numeric'));
            }
        }

        //validate the max price to search
        $pto = $this->input->post('pto');
        if (!empty($pto))
        {
            if (settype($pto, 'double'))
            {
                $this->search->price_to = $pto;
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
        $keywords = $this->input->post('keywords');
        if (!empty($keywords) && is_string($keywords))
        {
            $this->search->set_keywords(urldecode($keywords));

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
        $cpath = $this->input->post('cPath');
        if (!empty($cpath) && is_numeric($cpath) && ($cpath > 0))
        {
            $this->search->category = $cpath;

            $recursive = $this->input->post('recursive');
            if (!empty($recursive))
            {
                $this->search->recursive = $recursive == '1' ? TRUE : FALSE;
            }
        }

        //set the manufacturer
        $manufacturers = $this->input->post('manufacturers');
        if (!empty($manufacturers) && is_numeric($manufacturers) && ($manufacturers > 0))
        {
            $this->search->manufacturer = $manufacturers;
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