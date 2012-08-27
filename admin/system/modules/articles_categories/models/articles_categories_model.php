<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource modules/articles_categories/models/articles_categories_model.php
 */


class Articles_Categories_Model extends CI_Model
{
  public function get_articles_categories($start = NULL, $limit = NULL)
  {
    $this->query();
    
    if ($start !== NULL && $limit !== NULL)
    {
      $this->db->limit($limit, $start > 0 ? $start - 1 : $start);
    }
    
    $Qcategories = $this->db->get();
    
    return $Qcategories->result_array();
  }
  
  public function get_articles($id)
  {
    $Qarticles = $this->db
    ->select('count(articles_id) as num_of_articles')
    ->from('articles')
    ->where('articles_categories_id', $id)
    ->get();
    
    $data = $Qarticles->row_array();
    $Qarticles->free_result();
    
    return $data['num_of_articles'];
  }
  
  public function delete($id)
  {
    if (is_numeric($id))
    {
      $this->db->trans_begin();
      
      $this->db->delete('articles_categories', array('articles_categories_id' => $id));
      
      if ($this->db->trans_status() === TRUE)
      {
        $this->db->delete('articles_categories_description', array('articles_categories_id' => $id));
      } 
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      if ($this->cache->get('box-article-categories'))
      {
        $this->cache->delete('box-article-categories');
      }
      
      if ($this->cache->get('sefu-article-categories'))
      {
        $this->cache->delete('sefu-article-categories');
      }
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_data($id, $language_id = NULL)
  {
    $language_id = empty($language_id) ? lang_id() : $language_id;
    
    $Qcategories = $this->db
    ->select('c.*, cd.*')
    ->from('articles_categories c')
    ->join('articles_categories_description cd', 'c.articles_categories_id = cd.articles_categories_id')
    ->where(array('c.articles_categories_id' => $id, 'cd.language_id' => $language_id))
    ->get();
    
    return $Qcategories->row_array();
  }
  
  public function set_status($id, $flag)
  {
    $this->db->update('articles_categories', array('articles_categories_status' => $flag), array('articles_categories_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      if ($this->cache->get('box-article-categories'))
      {
        $this->cache->delete('box-article-categories');
      }
      
      if ($this->cache->get('sefu-article-categories'))
      {
        $this->cache->delete('sefu-article-categories');
      }
      
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function save($id = NULL, $data)
  {
    $category_id = '';
    $error = FALSE;
    
    $this->db->trans_begin();
    
    $article_category = array('articles_categories_order' => $data['articles_order'], 
                              'articles_categories_status' => $data['status']);
    if (is_numeric($id))
    {
      $this->db->update('articles_categories', $article_category,  array('articles_categories_id' => $id));
    }
    else
    {
      $this->db->insert('articles_categories', $article_category);
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $articles_category_id = (is_numeric($id)) ? $id : $this->db->insert_id();
      
      foreach(lang_get_all() as $l)
      {
         $articles_category_description = array('articles_categories_name' => $data['name'][$l['id']], 
                                                'articles_categories_url' => ($data['url'][$l['id']] == '') ? $data['name'][$l['id']] : $data['url'][$l['id']], 
                                                'articles_categories_page_title' => $data['page_title'][$l['id']], 
                                                'articles_categories_meta_keywords' => $data['meta_keywords'][$l['id']], 
                                                'articles_categories_meta_description' => $data['meta_description'][$l['id']]);
        if (is_numeric($id))
        {
          $this->db->update('articles_categories_description', 
                            $articles_category_description, 
                            array('articles_categories_id' => $articles_category_id, 'language_id' => $l['id']));
        }
        else
        {
          $articles_category_description['articles_categories_id'] = $articles_category_id;
          $articles_category_description['language_id'] = $l['id'];
          
          $this->db->insert('articles_categories_description', $articles_category_description);
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
      
      if ($this->cache->get('box-article-categories'))
      {
        $this->cache->delete('box-article-categories');
      }
      
      if ($this->cache->get('sefu-article-categories'))
      {
        $this->cache->delete('sefu-article-categories');
      }
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_info($id)
  {
    $Qcategories = $this->db
    ->select('c.*, cd.*')
    ->from('articles_categories c')
    ->join('articles_categories_description cd', 'c.articles_categories_id = cd.articles_categories_id')
    ->where(array('c.articles_categories_id' => $id))
    ->get();
    
    return $Qcategories->result_array();
  }
  
  public function get_total()
  {
    $this->query();
    
    $Qtotal = $this->db->get();
    
    return $Qtotal->num_rows();
  }
  
  private function query()
  {
    $this->db
    ->select('c.articles_categories_id, c.articles_categories_status, cd.articles_categories_name, c.articles_categories_order')
    ->from('articles_categories c')
    ->join('articles_categories_description cd', 'c.articles_categories_id = cd.articles_categories_id and c.articles_categories_id > 1')
    ->where('cd.language_id', lang_id());
  }
} 


/* End of file articles_categories_model.php */
/* Location: ./system/modules/articles_categories/models/articles_categories_model.php */