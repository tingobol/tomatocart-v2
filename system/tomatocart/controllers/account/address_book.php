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
 * Address_Book Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Address_Book extends TOC_Controller {
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
    	  parent::__construct();
    	  
    	  //load model
    	  $this->load->model('address_model');
        $this->load->model('address_book_model');
    }
    
    /**
     * Default Function
     *
     * @access public
     */
    public function index()
    {
        //set page title
        $this->template->set_title(lang('address_book_heading'));
        
        //the customer has no default address
        if ($this->customer->has_default_address() === FALSE)
        {
            redirect(site_url('account/address_book/add'));
        }
        
        //setup view data
        $data['address_books'] = array();
        $address_books = $this->address_book_model->get_addresses($this->customer->get_id());
        if (!empty($address_books))
        {
            foreach($address_books as $address_book)
            {
                $address_book['format'] = $this->address_book_model->format($address_book, '<br />');
                
                if ($address_book['address_book_id'] == $this->customer->get_default_address_id())
                {
                    $data['primary_address_format'] = $address_book['format'];
                }
                
                $data['address_books'][] = $address_book;
            }
        }
        
        $data['default_address_id'] = $this->customer->get_default_address_id();
        
        //setup view
        $this->template->build('account/address_book', $data);
    }
    
    /**
     * Save Addressbook
     *
     * @access public
     */
    
    public function save()
    {
        $data = array();
        
        //validate gender
        if (config('ACCOUNT_GENDER') == '1')
        {
            $gender = $this->input->post('gender');
            
            if (!empty($gender) && (($gender == 'm') || ($gender == 'f')))
            {
                $data['entry_gender'] = $gender;
            }
        }
        else
        {
            $this->message_stack->add('address_book', lang('field_customer_gender_error'));
        }
        
        //validate firstname
        $firstname = trim($this->input->post('firstname'));
        if (!empty($firstname) && (strlen($firstname) >= config('ACCOUNT_FIRST_NAME')))
        {
            $data['entry_firstname'] = $firstname;
        }
        else
        {
            $this->message_stack->add('address_book', sprintf(lang('field_customer_first_name_error'), config('ACCOUNT_FIRST_NAME')));
        }
        
        //validate lastname
        $lastname = trim($this->input->post('lastname'));
        if (!empty($lastname) && (strlen($lastname) >= config('ACCOUNT_LAST_NAME')))
        {
            $data['entry_lastname'] = $lastname;
        }
        else
        {
            $this->message_stack->add('address_book', sprintf(lang('field_customer_last_name_error'), config('ACCOUNT_LAST_NAME')));
        }
        
        //validate company
        if (config('ACCOUNT_COMPANY') > -1)
        {
            $company = trim($this->input->post('company'));
            if (!empty($company) && (strlen($company) >= config('ACCOUNT_COMPANY')))
            {
                $data['entry_company'] = $company;
            }
            else
            {
                $this->message_stack->add('address_book', sprintf(lang('field_customer_company_error'), config('ACCOUNT_COMPANY')));
            }
        }
        
        //validate street address
        $street_address = trim($this->input->post('street_address'));
        if (!empty($street_address) && (strlen($street_address) >= config('ACCOUNT_STREET_ADDRESS')))
        {
            $data['entry_street_address'] = $street_address;
        }
        else
        {
            $this->message_stack->add('address_book', sprintf(lang('field_customer_street_address_error'), config('ACCOUNT_STREET_ADDRESS')));
        }
        
        //validate suburb
        if (config('ACCOUNT_SUBURB') >= 0)
        {
            $suburb = trim($this->input->post('suburb'));
            
            if (!empty($suburb) && (strlen($suburb) >= config('ACCOUNT_SUBURB')))
            {
                $data['entry_suburb'] = $suburb;
            }
            else
            {
                $this->message_stack->add('address_book', sprintf(lang('field_customer_suburb_error'), config('ACCOUNT_SUBURB')));
            }
        }
        
        //validate post code
        if (config('ACCOUNT_POST_CODE') >= -1)
        {
            $postcode = trim($this->input->post('postcode'));
            
            if (!empty($postcode) && (strlen($postcode) >= config('ACCOUNT_POST_CODE')))
            {
                $data['entry_postcode'] = $postcode;
            }
            else
            {
                $this->message_stack->add('address_book', sprintf(lang('field_customer_post_code_error'), config('ACCOUNT_POST_CODE')));
            }
        }
        
        //validate street address
        $city = trim($this->input->post('city'));
        if (!empty($city) && (strlen($city) >= config('ACCOUNT_CITY')))
        {
            $data['entry_city'] = $city;
        }
        else
        {
            $this->message_stack->add('address_book', sprintf(lang('field_customer_city_error'), config('ACCOUNT_CITY')));
        }
          
        //validate country
        $country = $this->input->post('country');
        if (!empty($country) && is_numeric($country) && ($country >= 1))
        {
            $data['entry_country_id'] = $country;
        }
        else
        {
            $this->message_stack->add('address_book', lang('field_customer_country_error'));
        }
        
        //validate state
        if (config('ACCOUNT_STATE') >= 0)
        {
            $state = $this->input->post('state');
            
            $zone_id = $this->address_model->get_zone_id($country, $state);
            
            if ($zone_id !== FALSE)
            {
                $data['entry_zone_id'] = $zone_id;
            }
            else
            {
                $this->message_stack->add('address_book', lang('field_customer_state_select_pull_down_error'));
            }
        }
      
        //validate telephone
        if (config('ACCOUNT_TELEPHONE') >= 0)
        {
            $telephone = trim($this->input->post('telephone'));
            if (!empty($telephone) && (strlen($telephone) >= config('ACCOUNT_TELEPHONE')))
            {
                $data['entry_telephone'] = $telephone;
            }
        }
        else
        {
            $this->message_stack->add('address_book', sprintf(lang('field_customer_telephone_number_error'), config('ACCOUNT_TELEPHONE')));
        }
        
        //validate fax
        if (config('ACCOUNT_FAX') >= 0)
        {
            $fax = trim($this->input->post('fax'));
            if (!empty($fax) && (strlen($fax) >= config('ACCOUNT_FAX')))
            {
                $data['entry_fax'] = $fax;
            }
        }
        else
        {
            $this->message_stack->add('address_book', sprintf(lang('field_customer_fax_number_error'), config('ACCOUNT_FAX')));
        }
        
        //validate primary option
        $primary = FALSE;
        if (($this->customer->has_default_address() === FALSE) || (!empty($this->input->post('primary')) && ($this->input->post('primary') == 1)))
        {
            $primary = TRUE;
        }
        
        //save the address book
        if ($this->message_stack->size('address_book') === 0)
        {
            if ($this->address_book_model->save($data, $this->customer->get_id(), empty($this->input->post('address_book_id')) ? NULL : $this->input->post('address_book_id'), $primary))
            {
                $this->message_stack->add_session('address_book', lang('success_address_book_entry_updated'), 'success');
                
                redirect(site_url('account/address_book'));
            }
            else
            {
                $this->message_stack->add('address_book', lang('error_database'));
            }
        }
        
        //setup view data
        $data['countries'] = $this->address_model->get_countries();
        $data['states'] = $this->address_model->get_states($this->input->post('country'));
        
        //editing the address book
        if (!empty($this->input->post('address_book_id')))
        {
            //set page title
            $this->template->set_title(lang('address_book_edit_entry_heading'));
                
            $data['address_book_id'] = $this->input->post('address_book_id');
            
            if ($this->customer->get_default_address_id() != $this->input->post('address_book_id'))
            {
                $data['display_primary'] = TRUE;
            }
        }
        else
        {
            //set page title
            $this->template->set_title(lang('address_book_add_entry_heading'));
        }
        
        //setup view
        $this->template->build('account/address_book_form', $data);
    }
    
    /**
     * Add Addressbook
     *
     * @access public
     */
    public function add()
    {
        //set page title
        $this->template->set_title(lang('address_book_add_entry_heading'));
        
        //the address book is full
        if ($this->address_book_model->number_of_entries($this->customer->get_id()) >= config('MAX_ADDRESS_BOOK_ENTRIES'))
        {
            $this->message_stack->add('address_book', lang('error_address_book_full'));
            
            //setup view
            $this->template->build('account/address_book_full');
        }
        else
        {
            //setup view data
            $data['countries'] = $this->address_model->get_countries();
            $data['states'] = $this->address_model->get_states(config('STORE_COUNTRY'));
            $data['gender'] = $this->customer->get_gender();
            $data['firstname'] = $this->customer->get_firstname();
            $data['lastname'] = $this->customer->get_lastname();
            $data['country_id'] = config('STORE_COUNTRY');
            
            if ($this->customer->has_default_address())
            {
                $data['display_primary'] = TRUE;
            }
            
            //setup view
            $this->template->build('account/address_book_form', $data);
        }
    }
    
    /**
     * Edit Addressbook
     *
     * @access public
     */
    public function edit()
    {
        if ($this->uri->segment(4) !== FALSE)
        {
            $address_book_id = $this->uri->segment(4);
            
            if ($this->address_book_model->check($address_book_id, $this->customer->get_id()) === FALSE)
            {
                $this->message_stack->add_session('address_book', lang('error_address_book_entry_non_existing'));
                
                redirect(site_url('account/address_book'));
            }
            else
            {
                //set page title
                $this->template->set_title(lang('address_book_edit_entry_heading'));
                
                //setup view data
                $data = $this->address_book_model->get_address($this->customer->get_id(), $address_book_id);
                $data['countries'] = $this->address_model->get_countries();
                $data['states'] = $this->address_model->get_states($data['country_id']);
                
                if ($this->customer->get_default_address_id() != $data['address_book_id'])
                {
                    $data['display_primary'] = TRUE;
                }
                
                //setup view
                $this->template->build('account/address_book_form', $data);
            }
        }
    }
    
    /**
     * Delete Addressbook
     *
     * @access public
     */
    public function delete()
    {
        if ($this->uri->segment(4) !== FALSE)
        {
            $address_book_id = $this->uri->segment(4);
            
            if ($address_book_id != $this->customer->get_default_address_id())
            {
                if ($this->address_book_model->delete($address_book_id, $this->customer->get_id()))
                {
                    $this->message_stack->add_session('address_book', lang('success_address_book_entry_deleted'), 'success');
                }
                else
                {
                    $this->message_stack->add_session('address_book', lang('error_databbase'));
                }
            }
            else
            {
                $this->message_stack->add_session('address_book', lang('warning_primary_address_deletion'), 'warining');
            }
        }
        
        redirect(site_url('account/address_book'));
    }
    
    /**
     * Handle the ajax request to get the states for the currenty selected country
     *
     * @access public
     * @return json
     */
    public function get_states()
    {
        $states = $this->address_model->get_states($this->input->post('country_id'));
        
        echo json_encode($states);
    }
}

/* End of file address_book.php */
/* Location: ./system/tomatocart/controllers/account/address_book.php */