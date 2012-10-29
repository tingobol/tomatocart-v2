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
 * Articles Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Articles_Model extends CI_Model
{
    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
// --------------------------------------------------------------------
    
    /**
     * Get Articles
     *
     * @access public
     * @param $start
     * @param $limit
     * @param $current_category_id
     * @return mixed
     */
    public function get_articles($start, $limit, $current_category_id, $search = NULL)
    {
        $this->query($current_category_id, $search);
        
        $result = $this->db
        ->order_by('a.articles_id')
        ->limit($limit, $start)
        ->get();
      
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        
        return NULL;
    }
    
// --------------------------------------------------------------------
  
    public function save($id = NULL, $data)
    {
      $error = FALSE;
      
      $this->db->trans_begin();
      
      $article_info = array('articles_status' => $data['articles_status'], 
                            'articles_order' => $data['articles_order'], 
                            'articles_categories_id' => $data['articles_categories']);
      
      if (is_numeric($id))
      {
        $article_info['articles_last_modified'] = date('Y-m-d H:i:s');
        
        $this->db->update('articles', $article_info, array('articles_id' => $id));
      }
      else
      {
        $article_info['articles_date_added'] = date('Y-m-d H:i:s');
        $article_info['articles_last_modified'] = date('Y-m-d H:i:s');
        
        $this->db->insert('articles', $article_info);
      }
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
      else
      {
        $articles_id = is_numeric($id) ? $id : $this->db->insert_id();
      }
      
      //Process Languages
      if ($error === FALSE)
      {
        foreach(lang_get_all() as $l)
        {
          $articles_description = array('articles_name' => $data['articles_name'][$l['id']], 
  //                                      'articles_url' => ($data['articles_url'][$l['id']] == '') ? $data['articles_name'][$l['id']] : $data['articles_url'][$l['id']], 
                                        'articles_description' => $data['articles_description'][$l['id']], 
                                        'articles_page_title' => $data['page_title'][$l['id']], 
                                        'articles_meta_keywords' => $data['meta_keywords'][$l['id']], 
                                        'articles_meta_description' => $data['meta_description'][$l['id']]);
          if (is_numeric($id))
          {
            $this->db->update('articles_description', $articles_description, array('articles_id' => $articles_id, 'language_id' => $l['id']));
          }
          else
          {
            $articles_description['articles_id'] = $articles_id;
            $articles_description['language_id'] = $l['id'];
            
            $this->db->insert('articles_description', $articles_description);
          }
          
          if ($this->db->trans_status() === FALSE)
          {
            $error = TRUE;
            
            break;
          }
        }
      }
      
      if ($error === FALSE)
      {
        $this->db->trans_commit();
        
        return TRUE;
      }
      
      $this->db->trans_rollback();
      
      return FALSE;
    }
    
// --------------------------------------------------------------------
    
    public function delete($id)
    {
        $this->db->trans_begin();
        
        $this->db->delete('articles_description', array('articles_id' => $id));
        
        if ($this->db->trans_status() === TRUE)
        {
          $this->db->delete('articles', array('articles_id' => $id));
        }
        
        if ($this->db->trans_status() === TRUE)
        {
          $this->db->trans_commit();
          
          return TRUE;
        }
        
        $this->db->trans_rollback();
        
        return FALSE;
    }
    
// --------------------------------------------------------------------
    
    public function set_status($id, $flag)
    {
        $this->db->update('articles', array('articles_status' => $flag, 
                                            'articles_last_modified' => date('Y-m-d H:i:s')), 
                                      array('articles_id' => $id));
                                      
        if ($this->db->affected_rows() > 0)
        {
          return TRUE;
        }
        
        return FALSE;
    }
    
// --------------------------------------------------------------------
    
    public function get_info($articles_id)
    {
        $articles = $this->db
        ->select('a.*, ad.*')
        ->from('articles a')
        ->join('articles_description ad', 'a.articles_id = ad.articles_id')
        ->where('a.articles_id', $articles_id)
        ->get();
        
        return $articles->result_array();
    }
    
// --------------------------------------------------------------------
    
    public function get_total($current_category_id, $search)
    {
        $this->query($current_category_id, $search);
        
        $Qtotal = $this->db->get();
        
        return $Qtotal->num_rows();
    }
    
// --------------------------------------------------------------------
    
    private function query($current_category_id, $search)
    {
        $this->db
        ->select('a.articles_id, a.articles_status, a.articles_order, ad.articles_name, acd.articles_categories_name')
        ->from('articles a')
        ->join('articles_description ad', 'a.articles_id = ad.articles_id and a.articles_id > 5')
        ->join('articles_categories_description acd', 'acd.articles_categories_id = a.articles_categories_id and acd.language_id = ad.language_id')
        ->where('ad.language_id', lang_id());
        
        if ($current_category_id > 0)
        {
          $this->db->where('a.articles_categories_id', $current_category_id);
        }
        
        if (!empty($search))
        {
          $this->db->like('ad.articles_name', $search);
        }
    }
} 

/* End of file articles_model.php */
/* Location: ./system/models/articles_model.php */