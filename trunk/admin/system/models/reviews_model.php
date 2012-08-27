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
 * @filesource ./system/modules/reviews/models/reviews_model.php
 */

class Reviews_Model extends CI_Model
{
  public function get_reviews($start, $limit)
  {
    $Qreviews = $this->db
    ->select('r.reviews_id, r.products_id, r.date_added, r.last_modified, r.reviews_rating, r.reviews_status, pd.products_name, l.code as languages_code')
    ->from('reviews r')
    ->join('products_description pd', 'r.products_id = pd.products_id and r.languages_id = pd.language_id', 'left')
    ->join('languages l', 'r.languages_id = l.languages_id')
    ->order_by('r.date_added desc')
    ->limit($limit, $start)
    ->get();
    
    return $Qreviews->result_array();
  }
  
  public function set_status($id, $flag)
  {
    $this->db->update('reviews', array('reviews_status' => $flag), array('reviews_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_data($id)
  {
    $Qreview = $this->db
    ->select('r.*, pd.products_name')
    ->from('reviews r')
    ->join('products_description pd', 'r.products_id = pd.products_id and r.languages_id = pd.language_id')
    ->where('r.reviews_id', $id)
    ->get();
    
    $data = $Qreview->row_array();
    $Qreview->free_result();
    
    $data['reviews_rating'] = image('images/stars_' . $data['reviews_rating'] . '.png', sprintf(lang('rating_from_5_stars'), $data['reviews_rating']));
    $data['detailed_rating'] = $data['reviews_rating'];
    
    $Qaverage = $this->db
    ->select_avg('reviews_rating')
    ->from('reviews')
    ->where('products_id', $data['products_id'])
    ->get();
    
    $average = $Qaverage->row_array();
    $Qaverage->free_result();
    
    $data['average_rating'] = $average['reviews_rating'] / 5 * 100;
    
    $ratings = $this->get_customers_ratings($id);
    
    if (is_array($ratings) && !empty($ratings))
    {
      $data['ratings'] = $ratings;
    }
    else
    {
      $data['ratings'] = NULL;
    }
    
    return $data;
  }
  
  public function get_customers_ratings($reviews_id)
  {
    $Qratings = $this->db
    ->select('r.customers_ratings_id, r.ratings_id, r.ratings_value, rd.ratings_text')
    ->from('customers_ratings r')
    ->join('ratings_description rd', 'r.ratings_id = rd.ratings_id')
    ->where(array('r.reviews_id' => $reviews_id, 'rd.languages_id' => lang_id()))
    ->order_by('r.customers_ratings_id')
    ->get();
    
    $ratings = array();
    if ($Qratings->num_rows() > 0)
    {
      foreach($Qratings->result_array() as $rating)
      {
        $ratings[] = array('customers_ratings_id' => $rating['customers_ratings_id'],
                           'ratings_id' => $rating['ratings_id'],
                           'name'  => $rating['ratings_text'],
                           'value' => $rating['ratings_value']); 
      }
    }
    
    return $ratings;
  }
  
  public function save($id, $data)
  {
    $error = FALSE;
    
    $this->db->trans_begin();
    
    $this->db->update('reviews', array('reviews_text' => $data['review'], 
                                       'reviews_rating' => $data['rating'], 
                                       'reviews_status' => $data['reviews_status']), 
                                 array('reviews_id' => $id));
                                 
    if ($this->db->trans_status() === TRUE)
    {
      if (!empty($data['ratings']))
      {
        foreach($data['ratings'] as $customers_ratins_id => $value)
        {
          $this->db->update('customers_ratings', 
                            array('ratings_value' => $value), array('customers_ratings_id' => $customers_ratins_id));
                            
          if ($this->db->trans_status() === FALSE)
          {
            $error = TRUE;
            break;
          }
        }
      }
    }
    else
    {
      $error = TRUE;
    }

    if ($error === FALSE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function delete($id)
  {
    $this->db->trans_begin();
    
    $this->db->delete('reviews', array('reviews_id' => $id));
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('customers_ratings', array('reviews_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_total()
  {
    return $this->db->count_all('reviews');
  }
}

/* End of file reviews.php */
/* Location: ./system/modules/reviews/models/reviews_model.php */