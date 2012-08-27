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
 * Manufacturers Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-manufacturers-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Manufacturers_Model extends CI_Model
{
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
     * Get manufacturers
     *
     * @access public
     * @return array
     */
    public function get_manufacturers()
    {
        $result = $this->db
        ->select('manufacturers_id, manufacturers_name, manufacturers_image')
        ->from('manufacturers')
        ->order_by('manufacturers_name')
        ->get();
        
        return $result->result_array();
    }
}


/* End of file manufacturers_model.php */
/* Location: ./system/tomatocart/models/manufacturers_model.php */