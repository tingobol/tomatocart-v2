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
 * @filesource controllers/guest_book.php
 */

class Guest_Book extends TOC_Controller {
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('guest_book_model');
  }
  
  public function list_guest_book()
  {
    $start = $this->input->get_post('start');
    $limit = $this->input->get_post('limit');
    
    $start = empty($start) ? 0 : $start;
    $limit = empty($limit) ? MAX_DISPLAY_SEARCH_RESULTS : $limit;
    
    $guest_books = $this->guest_book_model->get_guest_books($start, $limit);
    
    $records = array();
    if (!empty($guest_books))
    {
      foreach($guest_books as $guest_book)
      {
        $records[]=  array('guest_books_id' => $guest_book['guest_books_id'],
                           'title' => $guest_book['title'],
                           'email'=> $guest_book['email'],
                           'guest_books_status' => $guest_book['guest_books_status'],
                           'languages' => show_image($guest_book['code']),
                           'content' => $guest_book['content'],
                           'date_added' => mdate('%Y/%m/%d', human_to_unix($guest_book['date_added'])));
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->guest_book_model->get_totals(),
                 EXT_JSON_READER_ROOT  => $records);
  }
  
  public function load_guest_book()
  {
    $data = $this->guest_book_model->get_data($this->input->get_post('guest_books_id'));
    
    return array('success' => true, 'data' => $data);
  }
  
  public function get_languages()
  {
    $languages = array();
    
    $languages_all = $this->lang->get_all();
    
    foreach ($languages_all as $l)
    {
      $languages[] = array('id' => $l['id'], 'text' => $l['name']);
    }
    
    return array(EXT_JSON_READER_ROOT => $languages);  
  }
  
  public function save_guest_book()
  {
    $data = array('guest_books_id' => $this->input->post('guest_books_id'), 
                  'title' => $this->input->post('title'), 
                  'email' => $this->input->post('email'), 
                  'url' => $this->input->post('url'), 
                  'content' => $this->input->post('content'), 
                  'languages_id' => $this->input->post('languages_id'), 
                  'guest_books_status' => $this->input->post('guest_books_status'));
    
    if ($this->guest_book_model->save($data))
    {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_guest_book()
  {
    $guest_book_id = $this->input->post('guest_books_id');
    
    if ($this->guest_book_model->delete($guest_book_id))
    {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_guest_books()
  {
    $batch = $this->input->post('batch');
    
    $guest_book_ids = json_decode($batch);
    
    if ($this->guest_book_model->batch_delete($guest_book_ids))
    {
      $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));  
    }
    
    return $response;
  }
  
  public function set_status()
  {
     $guest_book_id = $this->input->post('guest_books_id');
     $flag = $this->input->post('flag');

     if ($this->guest_book_model->set_status($guest_book_id, $flag))
     {
       $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
     }
     else
     {
       $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));  
     }
     
     return $response;
  }
}
