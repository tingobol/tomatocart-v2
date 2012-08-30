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
 * Weight Classes Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-module-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */
class Weight_Classes extends TOC_Controller
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
        
        $this->load->model('weight_classes_model');
    }
    
// ------------------------------------------------------------------------
    
    /**
     * List the weight classes
     *
     * @access public
     * @return string
     */
    public function list_weight_classes()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        
        $classes = $this->weight_classes_model->get_classes($start, $limit);
        
        $records = array();
        if ($classes != NULL)
        {
            foreach($classes as $class)
            {
                $class_name = $class['weight_class_title'];
                
                if ($class['weight_class_id'] == SHIPPING_WEIGHT_UNIT) 
                {
                    $class_name .= ' (' . lang('default_entry') . ')';
                }
                
                $records[] = array('weight_class_title' => $class_name,
                                  'weight_class_id' => $class['weight_class_id'],
                                  'weight_class_key' => $class['weight_class_key']);   
            }
        }
        
        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->weight_classes_model->get_total(),
                                                    EXT_JSON_READER_ROOT => $records)));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Get the weight classes rules
     *
     * @access public
     * @return string
     */
    public function get_weight_classes_rules()
    {
        $rules = $this->weight_classes_model->get_rules();
        
        if ($rules != NULL)
        {
            $response = array('success' => TRUE, 'rules' => $rules);
        }
        else
        {
            $response = array('success' => FALSE);
        }
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Save the weight classes
     *
     * @access public
     * @return string
     */
    public function save_weight_classes()
    {
        $data = array('name' => $this->input->post('name'),
                      'key' => $this->input->post('key'),
                      'rules' => $this->input->post('rules'));
        
        if ($this->weight_classes_model->save($this->input->post('weight_class_id'), $data, $this->input->post('is_default') == 'on' ? TRUE : FALSE))
        {
            $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed')); 
        }
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Load the weight classes
     *
     * @access public
     * @return string
     */
    public function load_weight_classes()
    {
        $classes_infos = $this->weight_classes_model->get_infos($this->input->post('weight_class_id'));
        
        $data = array();
        if ($classes_infos != NULL)
        {
            foreach($classes_infos as $class_info)
            {
                if ($class_info['language_id'] == lang_id())
                {
                    $data = array_merge($data, $class_info);
                    
                    if ($class_info['weight_class_id'] == SHIPPING_WEIGHT_UNIT)
                    {
                        $data['is_default'] = 1;
                    }
                }
                
                $data['name[' . $class_info['language_id'] . ']'] =  $class_info['weight_class_title'];
                $data['key[' . $class_info['language_id'] . ']'] = $class_info['weight_class_key'];
            }
            
            $rules_infos = $this->weight_classes_model->get_rules_infos($this->input->post('weight_class_id'));
        
            $rules = array();
            if ($rules_infos != NULL)
            {
                $rules = $rules_infos;
            }
            
            $data['rules'] = $rules;
            
            $response = array('success' => TRUE, 'data' => $data);
        }
        else
        {
            $response = array('success' => FALSE);
        }
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Delete the weight class
     *
     * @access public
     * @return string
     */
    public function delete_weight_class()
    {
        $error = false;
        $feedback = array();
        
        if ($this->input->post('weight_classes_id') == SHIPPING_WEIGHT_UNIT)
        {
            $error = true;
            $feedback[] = lang('delete_error_weight_class_prohibited');
        }
        else
        {
            $check_products = $this->weight_classes_model->get_products($this->input->post('weight_classes_id'));
            
            if ($check_products > 0)
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('delete_error_weight_class_in_use'), $check_products);
            }
        }
        
        if ($error === FALSE)
        {
            if ($this->weight_classes_model->delete($this->input->post('weight_classes_id')))
            {
                $response = array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
    
// ------------------------------------------------------------------------
    
    /**
     * Batch delete the weight classes
     *
     * @access public
     * @return string
     */
    public function delete_weight_classes()
    {
        $error = FALSE;
        $feedback = array();
        
        $weight_classes_ids = json_decode($this->input->post('batch'));
        
        foreach($weight_classes_ids as $id)
        {
            if ( $id == SHIPPING_WEIGHT_UNIT ) {
                $error = TRUE;
                $feedback[] = lang('delete_error_weight_class_prohibited');
            }
            else
            {
                $check_products = $this->weight_classes_model->get_products($id);
                
                if ($check_products > 0)
                {
                    $error = TRUE;
                    $feedback[] = lang('batch_delete_error_weight_class_in_use');
                    break;
                }
            }
        }
        
        if ($error === FALSE)
        {
            foreach($weight_classes_ids as $id)
            {
                if ($this->weight_classes_model->delete($id) === FALSE)
                {
                    $error = TRUE;
                    break;
                }
            }
            
            if ($error === FALSE) 
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            } 
            else 
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }
        
        $this->output->set_output(json_encode($response));
    }
}

/* End of file weight_classes.php */
/* Location: ./system/controllers/weight_classes.php */