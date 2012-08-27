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
 * @filesource models/guest_book.php
 */

class Guest_Book_Model extends CI_Model {
  public function __construct()
  {
    parent::__construct();
  }
  
  public function get_guest_book($start, $limit)
  {
    $records = array();
    
    $Qguest_book = $this->db
    ->select('guest_books_id, title, email, guest_books_status, gb.languages_id, content, date_added, l.code')
    ->from('guest_books gb')
    ->join('languages l', 'l.languages_id = gb.languages_id', 'inner')
    ->order_by('guest_books_id desc')
    ->limit($limit, $start > 0 ? $start -1 : $start)
    ->get();
    
    return $Qguest_book->result_array();
  }
  
  public function get_data($guest_books_id)
  {
    $data = array();
    
    $Qguest_book = $this->db
    ->select('*')
    ->from('guest_books')
    ->where('guest_books_id', $guest_books_id)
    ->get();
    
    return $Qguest_book->row_array();
  }
  
  public function save($data)
  {
    if (isset($data['guest_books_id']) && !empty($data['guest_books_id']))
    {
      $this->db->update('guest_books', $data, array('guest_books_id' => $data['guest_books_id']));
    }
    else
    {
      $this->db->insert('guest_books', $data);
    }
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function delete($guest_book_id)
  {
    $this->db->delete('guest_books', array('guest_books_id' => $guest_book_id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function batch_delete($guest_book_ids)
  {
    $this->db->where_in('guest_books_id', $guest_book_ids);
    $this->db->delete('guest_books');
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function set_status($guest_book_id, $flag)
  {
    $this->db->update('guest_books', array('guest_books_status' => $flag), array('guest_books_id' => $guest_book_id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_totals()
  {
    return $this->db->count_all('guest_books');
  }
} 