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
 * @filesource modules/articles/controllers/articles.php
 */

class Articles extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('articles_model');
  }
  
  public function list_articles()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $current_category_id = $this->input->get_post('current_category_id') ? $this->input->get_post('current_category_id') : 0;
    $search = $this->input->get_post('search');
    
    $articles = $this->articles_model->get_articles($start, $limit, $current_category_id, $search);
    
    $records = array();
    if (!empty($articles))
    {
      foreach($articles as $article)
      {
        $records[] = array('articles_id' => $article['articles_id'],
                           'articles_status' => $article['articles_status'],
                           'articles_order' => $article['articles_order'],
                           'articles_categories_name' => $article['articles_categories_name'],
                           'articles_name' => $article['articles_name']);
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->articles_model->get_total($current_category_id, $search),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function get_articles_categories()
  {
    $this->load->model('articles_categories_model');
    
    $article_categories = $this->articles_categories_model->get_articles_categories();
    
    $records = array();
    if ($this->input->get_post('top') == '1')
    {
      $records = array(array('id' => '', 'text' => lang('top_articles_category')));
    }
    
    if (!empty($article_categories))
    {
      foreach($article_categories as $category)
      {
        if ($category['articles_categories_id'] != '1') {
          $records[] = array('id' => $category['articles_categories_id'],
                             'text' => $category['articles_categories_name']);
        }
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function save_article()
  {
    $this->load->library('upload');
    $this->load->helper('html_output');
    $this->load->helper('directory');
    
    $data = array('articles_name' => $this->input->post('articles_name'),
                  'articles_description' => $this->input->post('articles_description'),
                  'articles_order' => $this->input->post('articles_order'),
                  'articles_status' => $this->input->post('articles_status'),
                  'delimage' => $this->input->post('delimage') == 'on' ? 1 : 0,
                  'articles_categories' => $this->input->post('articles_categories_id') ? $this->input->post('articles_categories_id') : '0',
                  'page_title' => $this->input->post('page_title'),
                  'meta_keywords' => $this->input->post('meta_keywords'),
                  'meta_description' => $this->input->post('meta_description'));
    
    if ($this->articles_model->save(($this->input->post('articles_id') != -1 ? $this->input->post('articles_id') : NULL), $data))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_article()
  {
    if ($this->articles_model->delete($this->input->post('articles_id')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_articles()
  {
    $error = FALSE;
    
    $articles_ids = json_decode($this->input->post('batch'));
    
    if (!empty($articles_ids))
    {
      foreach($articles_ids as $articles_id)
      {
        if ($this->articles_model->delete($articles_id) === FALSE)
        {
          $error = TRUE;
          break;
        }
      }
    }
    else
    {
      $error = TRUE;
    }
    
    if ($error === FALSE)
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function set_status()
  {
    if ($this->articles_model->set_status($this->input->post('articles_id'), $this->input->post('flag')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function load_article()
  {
    $articles_infos = $this->articles_model->get_info($this->input->post('articles_id'));
    
    $data = array();
    if (!empty($articles_infos))
    {
      foreach($articles_infos as $articles_info)
      {
        if ($articles_info['language_id'] == lang_id())
        {
          $data['articles_categories_id'] = $articles_info['articles_categories_id'];
          $data['articles_status'] = $articles_info['articles_status'];
          $data['articles_order'] = $articles_info['articles_order'];
          $data['articles_image'] = $articles_info['articles_image'];
        }
        
        $data['articles_name[' . $articles_info['language_id'] . ']'] = $articles_info['articles_name'];
//        $data['articles_url[' . $articles_info['language_id'] . ']'] = $articles_info['articles_url'];
        $data['articles_description[' . $articles_info['language_id'] . ']'] = $articles_info['articles_description'];
        $data['page_title[' . $articles_info['language_id'] . ']'] = $articles_info['articles_page_title'];
        $data['meta_keywords[' . $articles_info['language_id'] . ']'] = $articles_info['articles_meta_keywords'];
        $data['meta_description[' . $articles_info['language_id'] . ']'] = $articles_info['articles_meta_description'];
      }
    }
    
    return array('success' => TRUE, 'data' => $data);
  }
} 

/* End of file articles.php */
/* Location: ./system/modules/articles/controllers/articles.php */