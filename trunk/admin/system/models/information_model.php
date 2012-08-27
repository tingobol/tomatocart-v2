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
 * @filesource ./system/modules/information/models/information_model.php
 */

class Information_Model extends CI_Model
{
  public function get_articles($start, $limit)
  {
    $Qarticles = $this->db
    ->select('a.articles_id, a.articles_status, a.articles_order, ad.articles_name, acd.articles_categories_name')
    ->from('articles a')
    ->join('articles_description ad', 'a.articles_id = ad.articles_id')
    ->join('articles_categories_description acd', 'acd.articles_categories_id = a.articles_categories_id and acd.language_id = ad.language_id')
    ->where(array('acd.articles_categories_id' => 1, 'ad.language_id' => lang_id()))
    ->limit($limit, $start)
    ->get();
    
    return $Qarticles->result_array();
  }
  
  public function get_total()
  {
    return $this->db->where('articles_categories_id', 1)->from('articles')->count_all_results();
  }
}


/* End of file information_model.php */
/* Location: ./system/modules/information/models/information_model.php */