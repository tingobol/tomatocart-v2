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
     * Search query string
     *
     * @access private
     */
    private $searh_query_string = array();

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
        //page title
        $this->set_page_title(lang('search_heading'));

        $filters = $this->get_search_filters();

        if (($filters == NULL) && ($this->message_stack->size('search') > 0))
        {
            $this->load_advanced_search_form();
        }
        else
        {
            //load model
            $this->load->model('search_model');

            //
            $page = $this->input->get('p');
            $filters['page'] = (isset($page) && is_numeric($page)) ? ($page - 1) : 0;
            $filters['per_page'] = config('MAX_DISPLAY_SEARCH_RESULTS');

            //initialize pagination parameters
            $pagination['base_url'] = site_url('search?' . implode('&', $this->search_query_string));
            $pagination['total_rows'] = $this->search_model->count_products($filters);
            $pagination['per_page'] = config('MAX_DISPLAY_SEARCH_RESULTS');
            $pagination['use_page_numbers'] = TRUE;
            $pagination['page_query_string'] = TRUE;
            $pagination['query_string_segment'] = 'p';
            
            $pagination['full_tag_open'] = '<ul>';
            $pagination['full_tag_close'] = '</ul>';
            
            $pagination['first_tag_open'] = '<li>';
            $pagination['first_tag_close'] = '</li>';
            
            $pagination['last_tag_open'] = '<li>';
            $pagination['last_tag_close'] = '</li>';
            
            $pagination['cur_tag_open'] = '<li class="current"><a href="javascript:void(0);">';
            $pagination['cur_tag_close'] = '</a></li>';
            
            $pagination['next_tag_open'] = '<li>';
            $pagination['next_tag_close'] = '</li>';
            
            $pagination['prev_tag_open'] = '<li>';
            $pagination['prev_tag_close'] = '</li>';
            
            $pagination['num_tag_open'] = '<li>';
            $pagination['num_tag_close'] = '</li>';

            //load pagination library
            $this->load->library('pagination');
            $this->pagination->initialize($pagination);
            $data['links'] = $this->pagination->create_links();

            //get products
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

            $this->search_query_string[] = 'keywords=' . $keywords;
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

                $this->search_query_string[] = 'pfrom=' . $pfrom;
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

                $this->search_query_string[] = 'pto=' . $pto;
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

            $this->search_query_string[] = 'cPath=' . $cpath;

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
            
            $this->search_query_string[] = 'manufacturer=' . $manufacturers;
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
}

/* End of file search.php */
/* Location: ./system/tomatocart/controllers/search/search.php */