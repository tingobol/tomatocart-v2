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
 * Login Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Orders extends TOC_Controller
{
    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
        
        //load model
        $this->load->model('order_model');
        
        $this->lang->db_load('order');

        //set page title
        $this->template->set_title(lang('orders_heading'));
    }

    /**
     * Default Function
     *
     * @access public
     * @param string
     * @return void
     */
    public function index()
    {
        $orders = $this->order_model->get_orders($this->customer->get_id());
        
        if(count($orders) > 0)
        {
            for($i = 0; $i < count($orders); $i++)
            {
                $orders[$i]['number_of_products'] = $this->order_model->number_of_products($orders[$i]['orders_id']);
            }
        
        }
        
        $data['orders'] = $orders;
        
        //setup view
        $this->template->build('account/account_history.php', $data);
    }
    
    /**
     * 
     * @param unknown_type $orders_id
     */
    public function view($orders_id = NULL)
    {
        $data = $this->order_model->query($orders_id); 
        //setup view
        $this->template->build('account/account_history_info.php', $data);
    }
}

/* End of file login.php */
/* Location: ./system/tomatocart/controllers/account/login.php */