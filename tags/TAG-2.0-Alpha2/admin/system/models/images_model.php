<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource ./system/modules/images/models/images_model.php
 */

class Images_Model extends CI_Model
{
  public function get_products_images()
  {
    $Qimages = $this->db
    ->select('image')
    ->from('products_images')
    ->get();
    
    return $Qimages->result_array();
  }
} 

/* End of file images_model.php */
/* Location: ./system/modules/images/models/images_model.php */
