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
 * Account_Edit Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Edit extends TOC_Controller {
	/**
   * Constructor
   *
   * @access public
   */
	public function __construct()
	{
		  parent::__construct();
		  
		  //load model
      $this->load->model('account_model');
      
      //load helper
      $this->load->helper('date');
		  
      //set page title
      $this->template->set_title(lang('account_edit_heading'));
	}

	/**
   * Default Function
   *
   * @access public
   */
	public function index()
	{
      //setup view data
      $data = $this->account_model->get_data($this->customer->get_email_address());
      
      //setup view
      $this->template->build('account/account_edit', $data);
	}
	
	/**
   * Save the edited account
   *
   * @access public
   */
	public function save()
	{
      $data = array();
      
      //validate gender
      if (config('ACCOUNT_GENDER') == '1')
      {
          if (($this->input->post('gender') == 'm') || ($this->input->post('gender') == 'f'))
          {
              $data['customers_gender'] = $this->input->post('gender');
          }
          else
          {
              $this->message_stack->add('account_edit', lang('field_customer_gender_error'));
          }
      }
      else
      {
          $data['customers_gender'] = !empty($this->input->post('gender')) ? $this->input->post('gender') : '';
      }
      
      //validate firstname
      if (!empty($this->input->post('firstname')) || (strlen(trim($this->input->post('firstname'))) >= config('ACCOUNT_FIRST_NAME')))
      {
          $data['customers_firstname'] = $this->security->xss_clean($this->input->post('firstname'));
      }
      else
      {
          $this->message_stack->add('account_edit', sprintf(lang('field_customer_first_name_error'), config('ACCOUNT_FIRST_NAME')));
      }
      
      //validate lastname
      if (!empty($this->input->post('lastname')) || (strlen(trim($this->input->post('lastname'))) >= config('ACCOUNT_LAST_NAME')))
      {
          $data['customers_lastname'] = $this->security->xss_clean($this->input->post('lastname'));
      }
      else
      {
          $this->message_stack->add('account_edit', sprintf(lang('field_customer_last_name_error'), config('ACCOUNT_LAST_NAME')));
      }
      
      //validate dob days
      if (config('ACCOUNT_DATE_OF_BIRTH') == '1')
      {
          if (!empty($this->input->post('dob_days')))
          {
              $data['customers_dob'] = $this->input->post('dob_days');
          }
          else
          {
              $this->message_stack->add('account_edit', lang('field_customer_date_of_birth_error'));
          }
      }
      
      //email address
      if ((!empty($this->input->post('email_address'))) && (strlen(trim($this->input->post('email_address'))) >= config('ACCOUNT_EMAIL_ADDRESS')))
      {
          if (validate_email_address($this->input->post('email_address')))
          {
              if ($this->account_model->check_duplicate_entry($this->input->post('email_address')) === FALSE)
              {
                  $data['customers_email_address'] = $this->input->post('email_address');
              }
              else
              {
                  $this->message_stack->add('account_edit', lang('field_customer_email_address_exists_error'));
              }
          }
          else
          {
              $this->message_stack->add('account_edit', lang('field_customer_email_address_check_error'));
          }
      }
      else
      {
          $this->message_stack->add('account_edit', sprintf(lang('field_customer_email_address_error'), config('ACCOUNT_EMAIL_ADDRESS')));
      }
      
      //newsletter
      if (config('ACCOUNT_NEWSLETTER') == '1') 
      {
          $data['customers_newsletter'] = ($this->input->post('newsletter') == 1) ? '1' : '0';
      }
      
      if ($this->message_stack->size('account_edit') === 0)
      {
          if ($this->account_model->save($data, $this->customer->get_id()))
          {
              $this->customer->set_data($data['customers_email_address']);
              
              $this->message_stack->add_session('account', lang('success_account_updated'), 'success');
              
              redirect(site_url('account'));
          }
          else
          {
              $this->message_stack->add('account_edit', lang('error_database'));
          }
      }
      
      //setup view
      $this->template->build('account/account_edit');
	}
}

/* End of file edit.php */
/* Location: ./system/tomatocart/controllers/account/edit.php */