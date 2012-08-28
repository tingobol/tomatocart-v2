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
 * @filesource ./system/modules/information/controllers/information.php
 */

class Information extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('information_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('information_grid');
    $this->load->view('information_dialog');
    $this->load->view('information_general_panel');
    $this->load->view('information_meta_info_panel');
  }
  
  public function list_articles()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
     
    $articles = $this->information_model->get_articles($start, $limit);
    
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
    
    return array(EXT_JSON_READER_TOTAL => $this->information_model->get_total(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function set_status()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    $this->load->model('articles_model');
    
    if ($this->articles_model->set_status($this->input->post('aID'), $this->input->post('flag')))
    {
      if ($this->cache->get('box-information'))
      {
        $this->cache->delete('box-information');
      }
      
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
    $this->load->library(array('image', 'admin_image'));
    $this->load->driver('cache', array('adapter' => 'file'));
    $this->load->model('articles_model');
    
    if ($this->articles_model->delete($this->input->post('articles_id')))
    {
      if ($this->cache->get('box-information'))
      {
        $this->cache->delete('box-information');
      }
      
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
    $this->load->library(array('image', 'admin_image'));
    $this->load->driver('cache', array('adapter' => 'file'));
    $this->load->model('articles_model');
    
    $error = FALSE;
    
    $articles_ids = json_decode($this->input->post('batch'));
    foreach($articles_ids as $id)
    {
      if ($this->articles_model->delete($id) === FALSE)
      {
        $error = TRUE;
        break;
      }
    }
    
    if ($error === FALSE)
    {
      if ($this->cache->get('box-information'))
      {
        $this->cache->delete('box-information');
      }
      
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));    
    }
    
    return $response;
  }
  
  public function save_article()
  {
    $this->load->library(array('image', 'admin_image'));
    $this->load->library('upload');
    $this->load->helper('html_output');
    $this->load->helper('directory');
    $this->load->driver('cache', array('adapter' => 'file'));
    $this->load->model('articles_model');
    
    //search engine friendly urls
    $formatted_urls = array();
    $urls = $this->input->post('articles_url');
    if (is_array($urls) && !empty($urls))
    {
      foreach($urls as $languages_id => $url)
      {
        $url = format_friendly_url($url);
        
        if (empty($url))
        {
          $article_name = $this->input->post('articles_name');
          $url = format_friendly_url($article_name[$languages_id]);
        }
        
        $formatted_urls[$languages_id] = $url;
      }
    }
    
    $data = array('articles_name' => $this->input->post('articles_name'),
                  'articles_url' => $formatted_urls,
                  'articles_image' => 'articles_image',
                  'articles_description' => $this->input->post('articles_description'),
                  'articles_order' => $this->input->post('articles_order'),
                  'articles_status' => $this->input->post('articles_status'),
                  'delimage' => $this->input->post('delimage') == 'on' ? 1 : 0,
                  'articles_categories' => $this->input->post('articles_categories_id') ? $this->input->post('articles_categories_id') : '1',
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
    
    $this->output->set_header('Content-Type: text/html')->set_output(json_encode($response));
    
    return NULL;
  }
  
  public function load_article()
  {
    $this->load->model('articles_model');
    
    $articles_infos = $this->articles_model->get_info($this->input->post('articles_id'));
    
    $data = array();
    if (!empty($articles_infos))
    {
      foreach($articles_infos as $articles_info)
      {
        if ($articles_info['language_id'] == lang_id())
        {
          $data['articles_status'] = $articles_info['articles_status'];
          $data['articles_order'] = $articles_info['articles_order'];
          $data['articles_image'] = $articles_info['articles_image'];
        }
        
        $data['articles_name[' . $articles_info['language_id'] . ']'] = $articles_info['articles_name'];
        $data['articles_url[' . $articles_info['language_id'] . ']'] = $articles_info['articles_url'];
        $data['articles_description[' . $articles_info['language_id'] . ']'] = $articles_info['articles_description'];
        $data['page_title[' . $articles_info['language_id'] . ']'] = $articles_info['articles_page_title'];
        $data['meta_keywords[' . $articles_info['language_id'] . ']'] = $articles_info['articles_meta_keywords'];
        $data['meta_description[' . $articles_info['language_id'] . ']'] = $articles_info['articles_meta_description'];
      }
    }
    
    $response = array('success' => TRUE, 'data' => $data);
    
    return $response;
  }
}

/* End of file information.php */
/* Location: ./system/modules/information/controllers/information.php */