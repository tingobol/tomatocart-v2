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
 * Info Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-info-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Info_Model extends CI_Model
{

    /**
     * Constructor
     *
     * @access public
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the faqs
     *
     * @access public
     * @param int
     * @return array
     */
    public function get_faqs($languages_id)
    {
        $result = $this->db
        ->select('f.faqs_id, fd.faqs_question, fd.faqs_answer, fd.faqs_url')
        ->from('faqs f')
        ->join('faqs_description fd', 'f.faqs_id = fd.faqs_id')
        ->where(array('f.faqs_status' => 1, 'fd.language_id' => $languages_id))
        ->order_by('f.faqs_order desc, fd.faqs_question')
        ->get();

        return $result->result_array();
    }
    
    /**
     * Get the article category 
     *
     * @access public
     * @param int
     * @param int
     * @return array
     */
    public function get_article_category($categories_id, $language_id)
    {
        $result = $this->db
        ->select('articles_categories_name, articles_categories_page_title, articles_categories_meta_keywords, articles_categories_meta_description')
        ->from('articles_categories_description')
        ->where(array('articles_categories_id' => $categories_id, 'language_id' => $language_id))
        ->get();
        
        return $result->row_array();
    }
    
    /**
     * Get the articles
     *
     * @access public
     * @param int
     * @param int
     * @param int
     * @return array
     */
    public function get_articles($language_id, $categories_id = FALSE, $limit = FALSE)
    {
        $this->db
        ->select('a.articles_date_added, a.articles_last_modified, a.articles_image, a.articles_id, ad.articles_name, ad.articles_description')
        ->from('articles a')
        ->join('articles_description ad', 'a.articles_id = ad.articles_id')
        ->where(array('ad.language_id' => $language_id, 'a.articles_status' => 1));
        
        if (is_numeric($categories_id))
        {
            $this->db->where('a.articles_categories_id', $categories_id);
        }
        
        if (is_numeric($limit))
        {
            $this->db->limit($limit);
        }
        
        
        $result = $this->db->order_by('a.articles_id')->get();
        
        return $result->result_array();
    }
    
    /**
     * Get the articles categories
     *
     * @access public
     * @param int
     * @param int
     * @return array
     */
    public function get_articles_categories($language_id, $limit = FALSE)
    {
        $this->db
        ->select('cd.articles_categories_id, cd.articles_categories_name')
        ->from('articles_categories c')
        ->join('articles_categories_description cd', 'c.articles_categories_id = cd.articles_categories_id')
        ->where(array('cd.language_id' => $language_id, 'c.articles_categories_status' => 1))
        ->order_by('c.articles_categories_order, cd.articles_categories_name');
        
        if (is_numeric($limit))
        {
            $this->db->limit($limit);
        }
        
        $result = $this->db->get();
        
        return $result->result_array();
    }
    
    /**
     * Get the article
     *
     * @access public
     * @param int
     * @param int
     * @return array
     */
    public function get_article($articles_id, $language_id)
    {
        $result = $this->db
        ->select('a.articles_categories_id, acd.articles_categories_name, a.articles_image, ad.articles_name, ad.articles_description, ad.articles_page_title as page_title, ad.articles_meta_keywords as meta_keywords, ad.articles_meta_description as meta_description')
        ->from('articles a')
        ->join('articles_description ad', 'a.articles_id = ad.articles_id')
        ->join('articles_categories_description acd', 'a.articles_categories_id = acd.articles_categories_id and ad.language_id = acd.language_id')
        ->where(array('ad.language_id' => $language_id, 'a.articles_id' => $articles_id))
        ->get();
        
        return $result->row_array();
    }

}

/* End of file info_model.php */
/* Location: ./system/tomatocart/models/info_model.php */