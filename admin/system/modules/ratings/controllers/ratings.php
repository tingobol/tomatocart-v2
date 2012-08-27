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
 * @filesource ./system/modules/ratings/controllers/ratings.php
 */

class Ratings extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('ratings_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('ratings_grid');
    $this->load->view('ratings_dialog');
  }
  
  public function list_ratings()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $ratings = $this->ratings_model->get_ratings($start, $limit);
    
    $records = array();
    if (!empty($ratings))
    {
      foreach($ratings as $rating)
      {
        $records[] = array(
          'ratings_id' => $rating['ratings_id'],
          'ratings_name' => $rating['ratings_text'],
          'status' => $rating['status']
        );
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->ratings_model->get_total(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function set_status()
  {
    if ($this->ratings_model->set_status($this->input->post('ratings_id'), $this->input->post('flag')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function save_ratings()
  {
    $ratings_text = $this->input->post('ratings_text');
    
    $data = array('status' => $this->input->post('status'));
    foreach(lang_get_all() as $l)
    {
      $data['ratings_text'][$l['id']] = $ratings_text[$l['id']];
    }
    
    if ($this->ratings_model->save($this->input->post('ratings_id'), $data))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_rating()
  {
    if ($this->ratings_model->delete($this->input->post('ratings_id')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_ratings()
  {
    $error = FALSE;
    
    $ratins_ids = json_decode($this->input->post('batch'));
    
    foreach($ratins_ids as $id)
    {
      if (!$this->ratings_model->delete($id))
      {
        $error = TRUE;
        break;
      }
    }
    
    if ($error == FALSE) 
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    } 
    else 
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function load_ratings()
  {
    $ratings = $this->ratings_model->get_data($this->input->post('ratings_id'));
    
    $data = array();
    if (!empty($ratings))
    {
      foreach($ratings as $rating)
      {
        if ($rating['languages_id'] == lang_id())
        {
          $data['status'] = $rating['status'];
        }
        
        $data['ratings_text[' . $rating['languages_id'] .']'] = $rating['ratings_text'];
      }
    }
    
    return array('success' => TRUE, 'data' => $data);
  }
}

/* End of file ratings.php */
/* Location: ./system/modules/ratings/controllers/ratings.php */

