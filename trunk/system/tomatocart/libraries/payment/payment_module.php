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
 * Payment Module Class
 *
 * This class is the parent class for all payment modules
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class TOC_Payment_Module {

    /**
     * Module group
     * 
     * @access private
     * @var string
     */
    private $group = 'payment';
    
    /**
     * ci instance
     *
     * @access protected
     * @var object
     */
    protected $ci = NULL;

    /**
     * payment module code
     *
     * @access protected
     * @var string
     */
    protected $code = NULL;

    /**
     * payment module icon
     *
     * @access protected
     * @var string
     */
    protected $icon = NULL;

    /**
     * payment module title
     *
     * @access protected
     * @var string
     */
    protected $title = NULL;

    /**
     * payment module description
     *
     * @access protected
     * @var string
     */
    protected $description = NULL;

    /**
     * payment module status
     *
     * @access protected
     * @var boolean
     */
    protected $status = FALSE;

    /**
     * payment module sort order
     *
     * @access protected
     * @var int
     */
    protected $sort_order = 0;

    /**
     * order id
     *
     * @access protected
     * @var int
     */
    protected $order_id;

    /**
     * payment module configuration
     *
     * @access protected
     * @var array
     */
    protected $config = array();

    /**
     * payment module configuration parameters
     *
     * @access protected
     * @var array
     */
    protected $params = array();

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        // Load extension model
        $this->ci->load->model('extensions_model');

        // Get extension params
        $data = $this->ci->extensions_model->get_module('payment', $this->code);

        // Load data
        if ($data !== NULL)
        {
            $this->config = json_decode($data['params'], TRUE);
        }
    }
    
    /**
     * Install module
     * 
     * @access public
     * @return boolean
     */
    public function install() {
        //load extensions model
        $this->ci->load->model('extensions_model');
        
        //check whether the module is installed
        $data = $this->ci->extensions_model->get_module($this->group, $this->code);
        
        if ($data == NULL) {
            $data = array(
                'title' => $this->title,
                'code' => $this->code,
                'author_name' => '',
                'author_www' => '',
                'modules_group' => $this->group,
                'params' => json_encode($this->params));
            
            $result = $this->ci->extensions_model->install($data);
            
            //insert language definition
            if ($result) {
                $languages_all = $this->ci->lang->get_all();
                
                foreach ($languages_all as $l)
                {
                    $xml_file = '../system/tomatocart/language/' . $l['code'] . '/modules/' . $this->group . '/' . $this->code . '.xml';
                    $this->ci->lang->import_xml($xml_file, $l['id']);
                }
            }
            
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Uninstall module
     * 
     * @access public
     * @return boolean
     */
    public function uninstall() {
        //load extensions model
        $this->ci->load->model('extensions_model');

        $result = $this->ci->extensions_model->uninstall($this->group, $this->code);
        
        //remove language definition
        if ($result) {
            $languages_all = $this->ci->lang->get_all();
            
            foreach ($languages_all as $l)
            {
                $xml_file = '../system/tomatocart/language/' . $l['code'] . '/modules/' . $this->group . '/' . $this->code . '.xml';
                $this->ci->lang->remove_xml($xml_file, $l['id']);
            }
            
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Get Payment Module Code
     *
     * @access public
     * @return string payment module code
     */
    public function get_code()
    {
        return $this->code;
    }

    /**
     * Get Payment Module Title
     *
     * @access public
     * @return string payment module title
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * Get Payment Module Description
     *
     * @access public
     * @return string payment module description
     */
    public function get_description()
    {
        return $this->description;
    }

    /**
     * Get Payment Module Configurations
     *
     * @access public
     * @return array payment module configurations
     */
    public function get_config()
    {
        return $this->config;
    }

    /**
     * Get Payment Module Parameters
     *
     * @access public
     * @return array shipping module paramers
     */
    public function get_params()
    {
        return $this->params;
    }
    
    /**
     * Whether the payment module is installed
     *
     * @access public
     * @return boolean payment module installed
     */
    public function is_installed()
    {
        return is_array($this->config) && !empty($this->config);
    }
        
    /**
     * Whether the payment module is enabled
     *
     * @access public
     * @return boolean payment module status
     */
    public function is_enabled()
    {
        return $this->status;
    }

    /**
     * Get payment module sort order
     *
     * @access public
     * @return int payment module sort order
     */
    public function get_sort_order()
    {
        return $this->sort_order;
    }
    
    /**
     * Get selected payment module
     *
     * @access public
     * @return payment module selection
     */
    function selection()
    {
        return array('id' => $this->code, 'module' => $this->method_title);
    }

    /**
     * Process the payment module
     * 
     * @access public
     * @return void
     */
    public function process(){}

    /**
     * Process button
     * 
     * @access public
     * @return string
     */
    public function process_button() {}
}

/* End of file payment_module.php */
/* Location: ./system/tomatocart/libraries/payment/payment_module.php */