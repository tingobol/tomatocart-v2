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
 * @filesource ./system/modules/ratings/models/ratings_model.php
 */

class Ratings_Model extends CI_Model
{
  public function get_ratings($start, $limit)
  {
    $Qratings = $this->db
    ->select('r.ratings_id, r.status, rd.ratings_text')
    ->from('ratings r')
    ->join('ratings_description rd', 'r.ratings_id = rd.ratings_id')
    ->where('rd.languages_id', lang_id())
    ->limit($limit, $start)
    ->get();
    
    return $Qratings->result_array();
  }
  
  public function set_status($id, $status)
  {
    $this->db->update('ratings', array('status' => $status), array('ratings_id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function save($id = NULL, $data)
  {
    $error = FALSE;
    
    $this->db->trans_begin();
    
    if (is_numeric($id))
    {
      $this->db->update('ratings', array('status' => $data['status']), array('ratings_id' => $id));
    }
    else
    {
      $this->db->insert('ratings', array('status' => $data['status']));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $ratings_id = is_numeric($id) ? $id : $this->db->insert_id();
      
      foreach(lang_get_all() as $l)
      {
        if (is_numeric($id))
        {
          $this->db->update('ratings_description', 
                            array('ratings_text' => $data['ratings_text'][$l['id']]), 
                            array('ratings_id' => $id, 'languages_id' => $l['id']));
        }
        else
        {
          $this->db->insert('ratings_description', 
                            array('ratings_id' => $ratings_id, 
                                  'languages_id' => $l['id'], 
                                  'ratings_text' => $data['ratings_text'][$l['id']]));
        }
        
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
          break;
        }
      }
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
    $error = FALSE;
    
    $this->db->trans_begin();
    
    $this->db->delete('ratings', array('ratings_id' => $id));
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('ratings_description', array('ratings_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('categories_ratings', array('ratings_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('customers_ratings', array('ratings_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_data($id)
  {
    $Qratings = $this->db
    ->select('r.status, rd.ratings_text, rd.languages_id')
    ->from('ratings r')
    ->join('ratings_description rd', 'r.ratings_id = rd.ratings_id')
    ->where('r.ratings_id', $id)
    ->get();
    
    return $Qratings->result_array();
  }
  
  public function get_total()
  {
    return $this->db->count_all('ratings');
  }
}

/* End of file ratings_model.php */
/* Location: ./system/modules/ratings/models/ratings_model.php */
