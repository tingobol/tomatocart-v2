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
 * Condition Controller
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-info-controller
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Condition extends TOC_Controller {
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }
  
    /**
     * Default Function
     *
     * @access public
     */
    public function index()
    {
        //set page title
        $this->template->set_title(lang('info_conditions_heading'));
        
        //setup view
        $this->template->build('info/condition');
    }
}

/* End of file condition.php */
/* Location: ./system/tomatocart/controllers/info/condition.php */