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

require_once 'service_module.php';

/**
 * Free Shipping -- Shipping Module Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Service_debug extends TOC_Service_Module
{
    /**
     * Template Module Code
     *
     * @access private
     * @var string
     */
    var $code = 'debug';

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */

    /**
     * Template Module Params
     *
     * @access private
     * @var array
     */
    var $params = array(
    array('name' => 'SERVICE_DEBUG_EXECUTION_TIME_LOG',
              'title' => 'Page Execution Time Log File', 
              'type' => 'textfield',
              'value' => '',
              'description' => 'Location of the page execution time log file (eg, /www/log/page_parse.log).'),
    array('name' => 'SERVICE_DEBUG_EXECUTION_DISPLAY',
              'title' => 'Show The Page Execution Time', 
              'type' => 'combobox',
              'mode' => 'local',
		  	  'value' => '1',
              'description' => 'Show the page execution time.',
              'values' => array(
    array('id' => '1', 'text' => 'True'),
    array('id' => '-1', 'text' => 'False'))));
     
    /**
     * Constructor
     *
     * @access public
     */
    function __construct()
    {
        parent::__construct();

        $this->title = lang('services_debug_title');
        $this->description = lang('services_debug_description');
    }
}