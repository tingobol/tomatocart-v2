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
 * Ratings Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Ratings extends TOC_Controller
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
        
        $this->load->model('ratings_model');
    }
    
// ------------------------------------------------------------------------
    
    /**
     * List the ratings
     *
     * @access public
     * @return string
     */
    public function list_ratings()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $ratings = $this->ratings_model->get_ratings($start, $limit);
        
        $records = array();
        if ($ratings != NULL)
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
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->ratings_model->get_total(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Set the status of the rating
     *
     * @access public
     * @return string
     */
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
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Save the ratings
     *
     * @access public
     * @return string
     */
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
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Delete the rating
     *
     * @access public
     * @return string
     */
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
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Batch delete the ratings
     *
     * @access public
     * @return string
     */
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
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Load the ratings
     *
     * @access public
     * @return string
     */
    public function load_ratings()
    {
        $ratings = $this->ratings_model->get_data($this->input->post('ratings_id'));
        
        if ($ratings != NULL)
        {
            $data = array();
            foreach($ratings as $rating)
            {
                if ($rating['languages_id'] == lang_id())
                {
                    $data['status'] = $rating['status'];
                }
                
                $data['ratings_text[' . $rating['languages_id'] .']'] = $rating['ratings_text'];
            }
            
            $response = array('success' => TRUE, 'data' => $data);
        }
        else
        {
            $response = array('success' => FALSE);
        }
        
        $this->output->set_output(json_encode($response));
    }
}

/* End of file ratings.php */
/* Location: ./system/controllers/ratings.php */