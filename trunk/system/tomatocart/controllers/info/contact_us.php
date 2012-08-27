<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Contact Us Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-info-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Contact_Us extends TOC_Controller {
    /**
     * The view data
     *
     * @var array
     * @access private
     */
    private $_data = array();
    
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        
        //set page title
        $this->template->set_title(lang('info_contact_heading'));
        
        $this->load->model('departments_model');
        
        //setup view data
        $this->_data['departments'] = array();
        $departments = $this->departments_model->get_listing();
        
        if (!empty($departments))
        {
            foreach($departments as $department)
            {
                $this->_data['departments'][$department['departments_email_address']] = $department['departments_title'];
            }
        }
    }

    /**
     * Default Function
     *
     * @access public
     * @return void
     */
    public function index()
    {
        //setup view
        $this->template->build('info/contact_us', $this->_data);
    }
    
    /**
     * Save contact us
     *
     * @access public
     * @return void
     */
    public function save()
    {
        //validate department email
        if (!empty($this->input->post('department_email')))
        {
            $department_email = $this->security->xss_clean($this->input->post('department_email'));
            
            if (!validate_email_address($department_email))
            {
                $this->message_stack->add('contact', lang('field_departments_email_error'));
            }
        }
        else
        {
            $department_email = STORE_OWNER_EMAIL_ADDRESS;
        }
        
        //validate customer name field
        if (!empty($this->input->post('name')))
        {
            $name = $this->security->xss_clean($this->input->post('name'));
        }
        else
        {
            $this->message_stack->add('contact', lang('field_customer_name_error'));
        }
        
        //validate customer email field
        if (!empty($this->input->post('email')) && validate_email_address($this->input->post('email')))
        {
            $email = $this->security->xss_clean($this->input->post('email'));
        }
        else
        {
            $this->message_stack->add('contact', lang('field_customer_concat_email_error'));
        }
        
        //validate customer telephone
        if (!empty($this->input->post('telephone')))
        {
            $telephone = $this->security->xss_clean($this->input->post('telephone'));
        }
        
        //validate enquiry
        if (!empty($this->input->post('enquiry')))
        {
            $enquiry = $this->security->xss_clean($this->input->post('enquiry'));
        }
        else
        {
            $this->message_stack->add('contact', lang('field_enquiry_error'));
        }
        
        if ($this->message_stack->size('contact') === 0)
        {
            //ignore the send email action
            
            //setup view
            $this->template->build('info/contact_success', $this->_data);
        }
        else
        {
            //setup view
            $this->template->build('info/contact_us', $this->_data);
        }
    }
}

/* End of file contact_us.php */
/* Location: ./system/tomatocart/controllers/info/contact_us.php */