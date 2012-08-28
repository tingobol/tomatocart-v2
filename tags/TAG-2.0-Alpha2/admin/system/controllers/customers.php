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
 * Customers Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Customers extends TOC_Controller
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

        $this->load->model('customers_model');
    }

    // --------------------------------------------------------------------

    /**
     * List customers
     *
     * @access public
     * @return string
     */
    public function list_customers()
    {
        $start = $this->input->get_post('start');
        $limit = $this->input->get_post('limit');

        $start = empty($start) ? 0 : $start;
        $limit = empty($limit) ? MAX_DISPLAY_SEARCH_RESULTS : $limit;

        $search = $this->input->get_post('search');
        
        $records = array();

        $customers = $this->customers_model->get_customers($start, $limit, $search);
        if ($customers != NULL)
        {
            foreach($customers as $customer)
            {
                $customers_info =
                    '<table width="100%" cellspacing="5">' .
                        '<tbody>' . 
                            '<tr>
                                <td width="150">' . lang('field_gender') . '</td>
                                <td>' . ($customer['customers_gender'] == 'm' ? lang('gender_male') : lang('gender_female')) . '</td>
                            </tr>' . 
                            '<tr>
                                <td>' . lang('field_email_address') . '</td>
                                <td>' . $customer['customers_email_address'] . '</td>
                            </tr>' .
                            '<tr>
                                <td>' . lang('field_customers_group') . '</td>
                                <td>' . $customer['customers_groups_name'] . '</td>
                            </tr>' . 
                            '<tr>
                                <td>' . lang('field_number_of_logons') . '</td>
                                <td>' . $customer['number_of_logons'] . '</td>
                            </tr>' .
                            '<tr>
                                <td>' . lang('field_date_last_logon') . '</td>
                                <td>' . get_date_short($customer['date_last_logon']) . '</td>
                            </tr>' .
                        '</tbody>' .
                    '</table>';
                 
                $records[] = array(
                  'customers_id' => $customer['customers_id'],
                  'customers_lastname' => $customer['customers_lastname'],
                  'customers_firstname' => $customer['customers_firstname'],
                  'customers_credits' => $this->currencies->format($customer['customers_credits']),
                  'date_account_created' => $customer['date_account_created'],  
                  'customers_status' => $customer['customers_status'],
                  'customers_info' => $customers_info);           
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_TOTAL => $this->customers_model->get_totals($search), EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------

    /**
     * Delete customer
     *
     * @access public
     * @param $customers_id
     * @return string
     */
    public function delete_customer($customers_id)
    {
        $customers_id = $this->input->post('customers_id');

        if ($this->customers_model->delete($customers_id))
        {
            $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Set status
     *
     * @access public
     * @return string
     */
    public function set_status()
    {
        $flag = $this->input->post('flag');
        $customers_id = $this->input->post('customers_id');

        if ($this->customers_model->set_status($customers_id, $flag))
        {
            $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => true, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Get customers groups
     *
     * @access public
     * @return string
     */
    public function get_customers_groups()
    {
        $groups = $this->customers_model->get_customers_groups();

        $records = array(array('id' => '', 'text' => lang('none')));
        if ($groups != NULL)
        {
            foreach($groups as $group)
            {
                $records[] = array('id' => $group['customers_groups_id'],'text' => $group['customers_groups_name']);
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------

    /**
     * Save customer data
     *
     * @access public
     * @return string
     */
    public function save_customer()
    {
        $this->load->helper('email');

        $customers_dob = explode('-', $this->input->post('customers_dob'));
        $dob_year = $customers_dob[0];
        $dob_month = $customers_dob[1];
        $dob_date= $customers_dob[2];

        $customers_gender = $this->input->post('customers_gender');
        $customers_newsletter = $this->input->post('customers_newsletter');
        $customers_status = $this->input->post('customers_status');
        $customers_groups_id = $this->input->post('customers_groups_id');
        $customers_id = $this->input->post('customers_id');
        $confirm_password = $this->input->post('confirm_password');

        $data = array('gender' => (!empty($customers_gender) ? $customers_gender : ''),
                      'firstname' => $this->input->post('customers_firstname'),
                      'lastname' => $this->input->post('customers_lastname'),
                      'dob' => $this->input->post('customers_dob'),
                      'email_address' => $this->input->post('customers_email_address'),
                      'customers_password' => $this->input->post('customers_password'),
                      'newsletter' => (!empty($customers_newsletter) && ($customers_newsletter == 'on') ? '1' : '0' ),           
                      'status' => (!empty($customers_status) && ($customers_status == 'on') ? '1' : '0'),
                      'customers_groups_id' => ( !empty($customers_groups_id) ? $customers_groups_id : '') );

        $error = FALSE;
        $feedback = array();

        //customer gender
        if ( ACCOUNT_GENDER > 0 )
        {
            if ( ($data['gender'] != 'm') && ($data['gender'] != 'f') )
            {
                $error = TRUE;
                $feedback[] = lang('ms_error_gender');
            }
        }

        //customer firstname
        if ( strlen(trim($data['firstname'])) < ACCOUNT_FIRST_NAME )
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_first_name'), ACCOUNT_FIRST_NAME);
        }

        //customer lastname
        if ( strlen(trim($data['lastname'])) < ACCOUNT_LAST_NAME )
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_last_name'), ACCOUNT_LAST_NAME);
        }

        //customer email address
        if ( strlen(trim($data['email_address'])) < ACCOUNT_EMAIL_ADDRESS )
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_email_address'), ACCOUNT_EMAIL_ADDRESS);
        }
        elseif ( !valid_email($data['email_address']) )
        {
            $error = TRUE;
            $feedback[] = lang('ms_error_email_address_invalid');
        }
        else
        {
            $check = $this->customers_model->check($data['email_address'], $customers_id);

            if ($check > 0)
            {
                $error = TRUE;
                $feedback[] = lang('ms_error_email_address_exists');
            }
        }

        //password
        if ( (empty($customers_id) || !empty($data['customers_password'])) && (strlen(trim($data['customers_password'])) < ACCOUNT_PASSWORD) )
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_password'), ACCOUNT_PASSWORD);
        }
        else if ( !empty($confirm_password) && ( (trim($data['customers_password']) != trim($confirm_password)) || ( strlen(trim($data['customers_password'])) != strlen(trim($confirm_password)) ) ) )
        {
            $error = TRUE;
            $feedback[] = lang('ms_error_password_confirmation_invalid');
        }

        //save customer data
        if ($error === FALSE)
        {
            if ($this->customers_model->save( (!empty($customers_id) && is_numeric($customers_id) ? $customers_id : NULL), $data))
            {
                $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Load customer
     *
     * @access public
     * @return string
     */
    public function load_customer()
    {
        $customers_id = $this->input->post('customers_id');

        $data = $this->customers_model->get_data($customers_id);
        
        if ($data != NULL) 
        {
            $data['customers_dob'] = mdate('%Y-%m-%d', human_to_unix($data['customers_dob']));
            $data['customers_password'] = '';
            $data['confirm_password'] = '';
        }

        $this->output->set_output(json_encode(array('success' => true, 'data' => $data)));
    }

    // --------------------------------------------------------------------

    /**
     * List address books
     *
     * @access public
     * @return string
     */
    public function list_address_books()
    {
        //load address library
        $this->load->library('address');

        $customers_id = $this->input->get_post('customers_id');
        $addresses = $this->customers_model->get_addressbook_data($customers_id);

        $records = array();
        if (!empty($addresses))
        {
            foreach($addresses as $address)
            {
                $address_html= $this->address->format($address, '<br />');

                $records[] = array(
                    'address_book_id' => $address['address_book_id'],
                    'address_html' => $address_html
                );
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------

    /**
     * Delete address book
     *
     * @access public
     * @return string
     */
    public function delete_address_book()
    {
        $address_book_id = $this->input->post('address_book_id');

        if ($this->customers_model->delete_address($address_book_id))
        {
            $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Get countries
     *
     * @access public
     * @return string
     */
    public function get_countries()
    {
        $this->load->library('address');

        //get countries
        $countries = $this->address->get_countries();
        
        $records = array();
        if ($countries != NULL) 
        {
            foreach ($countries as $country)
            {
                $records[] = array('country_id' => $country['id'], 'country_title' => $country['name']);
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------

    /**
     * Get zones
     *
     * @access public
     * @return string
     */
    public function get_zones()
    {
        $this->load->library('address');

        $country_id = $this->input->get_post('country_id');
        $zones = $this->address->get_zones($country_id);

        $records = array();
        if ($zones != NULL) 
        {
            foreach ($zones as $zone) {
                $records[] = array(
                    'zone_code' => $zone['code'],
                    'zone_name' => $zone['name']);
            }
        }

        $this->output->set_output(json_encode(array(EXT_JSON_READER_ROOT => $records)));
    }

    // --------------------------------------------------------------------

    /**
     * Save address book
     *
     * @access public
     * @return string
     */
    public function save_address_book()
    {
        $gender = $this->input->post('gender');
        $company = $this->input->post('company');
        $suburb = $this->input->post('suburb');
        $postcode = $this->input->post('postcode');
        $z_code = $this->input->post('z_code');
        $telephone_number = $this->input->post('telephone_number');
        $fax_number = $this->input->post('fax_number');
        $primary= $this->input->post('primary');

        $data = array(
            'customer_id' => $this->input->post('customers_id'),
            'gender' => (!empty($gender) ? $gender : ''),
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'company' => (!empty($company) ? $company : ''),
            'street_address' => $this->input->post('street_address'),
            'suburb' => (!empty($suburb) ? $suburb : ''),
            'postcode' => (!empty($postcode) ? $postcode : ''),
            'city' => $this->input->post('city'),
            'state' => (!empty($z_code) ? $z_code : ''),
            'zone_id' => '0', //set blow
            'country_id' => $this->input->post('country_id'),
            'telephone' => (!empty($telephone_number) ? $telephone_number : ''),
            'fax' => (!empty($fax_number) ? $fax_number : ''),
            'primary' => (!empty($primary) && ($primary == 'on') ? TRUE : FALSE));

        $error = FALSE;
        $feedback = array();

        if ( ACCOUNT_GENDER > 0 )
        {
            if ( ($data['gender'] != 'm') && ($data['gender'] != 'f') )
            {
                $error = TRUE;
                $feedback[] = lang('ms_error_gender');
            }
        }

        if ( strlen(trim($data['firstname'])) < ACCOUNT_FIRST_NAME )
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_first_name'), ACCOUNT_FIRST_NAME);
        }

        if ( strlen(trim($data['lastname'])) < ACCOUNT_LAST_NAME )
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_last_name'), ACCOUNT_LAST_NAME);
        }


        if ( ACCOUNT_COMPANY > 0 )
        {
            if ( strlen(trim($data['company'])) < ACCOUNT_COMPANY )
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('ms_error_company'), ACCOUNT_COMPANY);
            }
        }

        if ( strlen(trim($data['street_address'])) < ACCOUNT_STREET_ADDRESS )
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_street_address'), ACCOUNT_STREET_ADDRESS);
        }

        if ( ACCOUNT_SUBURB > 0 )
        {
            if ( strlen(trim($data['suburb'])) < ACCOUNT_SUBURB )
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('ms_error_suburb'), ACCOUNT_SUBURB);
            }
        }

        if ( ACCOUNT_POST_CODE > 0 )
        {
            if ( strlen(trim($data['postcode'])) < ACCOUNT_POST_CODE )
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('entry_post_code'), ACCOUNT_POST_CODE);
            }
        }

        if ( strlen(trim($data['city'])) < ACCOUNT_CITY )
        {
            $error = TRUE;
            $feedback[] = sprintf(lang('ms_error_city'), ACCOUNT_CITY);
        }

        if ( ACCOUNT_STATE > 0 )
        {
            $zones_nums = $this->customers_model->get_state_zones($data['country_id']);

            if ($zones_nums > 0)
            {
                $zones = $this->customers_model->get_zones($data['country_id'], strtoupper($data['state']));

                if (count($zones) === 1)
                {
                    $data['zone_id'] = $zones[0]['zone_id'];
                }
                else
                {
                    $zone_likes = $this->customers_model->get_zone_likes($data['country_id'], $data['state']);

                    if (count($zone_likes) === 1)
                    {
                        $data['zone_id'] = $zone_likes[0]['zone_id'];
                    }
                    else
                    {
                        $error = TRUE;
                        $feedback[] = lang('ms_warning_state_select_from_list');
                    }
                }
            }
            else if (strlen(trim($data['state'])) < ACCOUNT_STATE )
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('ms_error_state'), ACCOUNT_STATE);
            }
        }

        if ( !is_numeric($data['country_id']) || ($data['country_id'] < 1) ) 
        {
            $error = TRUE;
            $feedback[] = lang('ms_error_country');
        }

        if ( ACCOUNT_TELEPHONE > 0 ) 
        {
            if ( strlen(trim($data['telephone'])) < ACCOUNT_TELEPHONE ) 
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('ms_error_telephone_number'), ACCOUNT_TELEPHONE);
            }
        }

        if ( ACCOUNT_FAX > 0 ) 
        {
            if ( strlen(trim($data['fax'])) < ACCOUNT_FAX ) 
            {
                $error = TRUE;
                $feedback[] = sprintf(lang('ms_error_fax_number'), ACCOUNT_FAX);
            }
        }

        if ($error === FALSE ) 
        {
            $address_book_id = $this->input->post('address_book_id');
            $address_book_id = (!empty($address_book_id) && is_numeric($address_book_id)) ? $address_book_id : NULL;

            if ( $this->customers_model->save_address($address_book_id, $data) ) 
            {
                $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
            } 
            else 
            {
                $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
            }
        } 
        else 
        {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Delete address books
     *
     * @access public
     * @return string
     */
    public function delete_address_books()
    {
        $error = FALSE;

        $batch = json_decode($this->input->post('batch'));

        if (is_array($batch) && !empty($batch))
        {
            foreach($batch as $id)
            {
                if (!$this->customers_model->delete_address($id))
                {
                    $error = TRUE;
                    break;
                }
            }
        }

        if ($error === FALSE)
        {
            $response = array('success' => true, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => false, 'feedback' => lang('ms_error_action_not_performed'));
        }

        $this->output->set_output(json_encode($response));
    }

    // --------------------------------------------------------------------

    /**
     * Load address book
     *
     * @access public
     * @return string
     */
    public function load_address_book()
    {
        $customers_id = $this->input->post('customers_id');
        $address_book_id = $this->input->post('address_book_id');
        
        $data = $this->customers_model->get_addressbook_data($customers_id, $address_book_id);

        $data['primary'] = FALSE;
        $customer = $this->customers_model->get_data($customers_id);
        if ($customer['customers_default_address_id'] == $address_book_id) 
        {
            $data['primary'] = TRUE;
        }

        $this->output->set_output(json_encode(array('success' => true, 'data' => $data)));
    }
}

/* End of file customers.php */
/* Location: ./system/tomatocart/controllers/customers.php */