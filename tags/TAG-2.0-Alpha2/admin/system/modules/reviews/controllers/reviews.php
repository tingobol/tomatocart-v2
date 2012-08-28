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
 * @filesource ./system/modules/reviews/controllers/reviews.php
 */

class Reviews extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('reviews_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('reviews_grid');
    $this->load->view('reviews_edit_dialog');
  }
  
  public function list_reviews()
  {
    $this->load->helper('date');
    $this->load->helper('html_output');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $reviews = $this->reviews_model->get_reviews($start, $limit);
    
    $records = array();
    if (!empty($reviews))
    {
      foreach($reviews as $review)
      {
        $records[] = array('reviews_id' => $review['reviews_id'],
                           'date_added' => mdate('%Y/%m/%d', human_to_unix($review['date_added'])),
                           'reviews_rating' => image('images/stars_' . $review['reviews_rating'] . '.png', sprintf(lang('rating_from_5_stars'), $review['reviews_rating'])),
                           'products_name' => $review['products_name'],
                           'reviews_status' => $review['reviews_status'],
                           'code' => show_image($review['languages_code']));
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->reviews_model->get_total(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function set_status()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    if ($this->reviews_model->set_status($this->input->post('reviews_id'), $this->input->post('flag')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function load_reviews()
  {
    $this->load->helper('date');
    $this->load->helper('html_output');
    
    $data = $this->reviews_model->get_data($this->input->post('reviews_id'));
    
    $data['date_added'] = mdate('%Y/%m/%d', human_to_unix($data['date_added']));
    
    return array('success' => TRUE, 'data' => $data);
  }
  
  public function save_reviews()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    $total = 0;
    $data = array('review' => $this->input->post('reviews_text'), 'reviews_status' => $this->input->post('reviews_status'));
    
    $ratings = array();
    foreach($this->input->post() as $key => $value)
    {
      if (substr($key, 0, 13) == 'ratings_value') 
      {
        $customers_ratings_id = substr($key, 13);
        
        $ratings[$customers_ratings_id] = $value;
        $total += $value;
      }
    }
    
    if (count($ratings) > 0) 
    {
      $data['rating'] = $total / count($ratings);
      $data['ratings'] = $ratings;
    } 
    else 
    {
      $data['rating'] = $this->input->post('detailed_rating');
    }
    
    if ($this->reviews_model->save($this->input->post('reviews_id'), $data))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));    
    }
    
    return $response;
  }
  
  public function delete_review()
  {
    if ($this->reviews_model->delete($this->input->post('reviews_id')))
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_reviews()
  {
    $error = FALSE;
    
    $reviews_ids = json_decode($this->input->post('batch'));
    
    foreach($reviews_ids as $id)
    {
      if (!$this->reviews_model->delete($id))
      {
        $error = TRUE;
        break;
      }
    }
    
    if ($error === FALSE) 
    {
      $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
    } 
    else 
    {
      $response = array('success' => FALSE ,'feedback' => lang('ms_error_action_not_performed'));               
    }
    
    return $response;
  }
}

/* End of file reviews.php */
/* Location: ./system/modules/reviews/controllers/reviews.php */