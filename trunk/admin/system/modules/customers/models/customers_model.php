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
 * @filesource customers_model.php
 */

class Customers_Model extends CI_Model {
  public function __construct()
  {
    parent::__construct();
  }
  
  public function get_customers($start, $limit, $search)
  {
    $this->db
    ->select('c.customers_id, c.customers_credits, c.customers_gender, c.customers_lastname, c.customers_firstname, c.customers_email_address, c.customers_status, c.customers_ip_address, c.date_account_created, c.number_of_logons, c.date_last_logon, cgd.customers_groups_name')
    ->from('customers c')
    ->join('customers_groups_description cgd', 'c.customers_groups_id = cgd.customers_groups_id and cgd.language_id = ' . lang_id(), 'left');
         
    if (!empty($search))
    {
      $this->db->like('c.customers_lastname', $search)
      ->or_like('c.customers_firstname', $search)
      ->like('c.customers_email_address', $search);
    }
    
    $Qcustomers = $this->db
    ->order_by('c.customers_lastname, c.customers_firstname')
    ->limit($limit, $start > 0 ? $start -1 : $start)
    ->get();
    
    $records = array();
    if ($Qcustomers->num_rows() > 0)
    {
      foreach($Qcustomers->result_array() as $customer)
      {
         $customers_info = 
          '<table width="100%" cellspacing="5">' .
            '<tbody>' . 
              '<tr>
                <td width="150">' . lang('field_gender') . '</td>
                <td>' . ($customer['customers_gender'] == 'm' ? lang('gender_male') : lang('gender_female')) . '</td>
              </tr>' . 
              '<tr>
                <td>' . lang('field_email_address') . '</td>
                <td>' . $customer['customers_email_address'] . '</td>
              </tr>' .
              '<tr>
                <td>' . lang('field_customers_group') . '</td>
                <td>' . $customer['customers_groups_name'] . '</td>
              </tr>' . 
              '<tr>
                <td>' . lang('field_number_of_logons') . '</td>
                <td>' . $customer['number_of_logons'] . '</td>
              </tr>' .
              '<tr>
                <td>' . lang('field_date_last_logon') . '</td>
                <td>' . mdate('%d/%m/%Y', human_to_unix($customer['date_last_logon'])) . '</td>
              </tr>' .
            '</tbody>' .
          '</table>';
         
        $records[] = array(
          'customers_id' => $customer['customers_id'],
          'customers_lastname' => $customer['customers_lastname'],
          'customers_firstname' => $customer['customers_firstname'],
          'customers_credits' => $this->currencies->format($customer['customers_credits']),
          'date_account_created' => $customer['date_account_created'],  
          'customers_status' => $customer['customers_status'],
          'customers_info' => $customers_info);           
      } 
    }
    
    return $records;
  }
  
  public function delete($id, $delete_reviews = TRUE)
  {
    $this->db->trans_begin();
    
    if ($delete_reviews === TRUE)
    {
      $this->db->delete('reviews', array('customers_id' => $id));
    }
    else
    {
      $Qcheck = $this->db
      ->select('reviews_id')
      ->from('reviews')
      ->where('customers_id', $id)
      ->limit(1)
      ->get();
      
      if ($Qcheck->num_rows > 0)
      {
        $this->db->update('reviews', array('customers_id' => NULL), array('customers_id' => $id));
      }
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('address_book', array('customers_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('customers_credits_history', array('customers_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $tbl_wp = $this->db->protect_identifiers('wishlists_products', TRUE);
      $tbl_w = $this->db->protect_identifiers('wishlists', TRUE);
      
      $sql = 'delete from ' . $tbl_wp . 'where wishlists_id = (select wishlists_id from ' . $tbl_w . ' where customers_id = ?)';
      $this->db->query($sql, array((int)$id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('wishlists', array('customers_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('customers_basket', array('customers_id' => $id));
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('products_notifications', array('customers_id' => $id));
    }
    
    //drop the check action for whos_online table
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->delete('customers', array('customers_id' => $id));
    }
    
    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      
      return FALSE;
    }
    else
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
  }
  
  public function set_status($customers_id, $flag)
  {
    $this->db->update('customers', array('customers_status' => $flag), array('customers_id' => $customers_id));
    
    return true;
  }
  
  public function get_totals($search)
  {
    if (empty($search))
    {
      return $this->db->count_all('customers');
    }
    else
    {
      $Qtotal = $this->db
      ->select('customers_id')
      ->from('customers')
      ->like('customers_lastname', $search)
      ->or_like('customers_firstname', $search)
      ->like('customers_email_address', $search)
      ->get();
      
      return $Qtotal->num_rows();
    }
  }
  
  public function get_customers_groups()
  {
    $Qgroups = $this->db
    ->select('cg.customers_groups_id, cg.is_default, cgd.customers_groups_name')
    ->from('customers_groups cg')
    ->join('customers_groups_description cgd', 'cg.customers_groups_id = cgd.customers_groups_id', 'inner')
    ->where('language_id', lang_id())
    ->order_by('cg.customers_groups_id')
    ->get();
    
    return $Qgroups->result_array();
  }
  
  public function check($email_address, $customers_id)
  {
    $this->db
    ->select('customers_id')
    ->from('customers')
    ->where('customers_email_address', $email_address);
    
    if (!empty($customers_id) && is_numeric($customers_id))
    {
      $this->db->where('customers_id !=', $customers_id);
    }
    
    $Qcheck = $this->db->limit(1)->get();
    
    return $Qcheck->num_rows();
  }
  
  public function save($id = null, $data, $send_email = true)
  {
    $this->db->trans_begin();
    
    $data = array('customers_gender' => $data['gender'], 
                  'customers_firstname' => $data['firstname'], 
                  'customers_lastname' => $data['lastname'], 
                  'customers_email_address' => $data['email_address'], 
                  'customers_dob' => $data['dob'],
                  'customers_newsletter' => $data['newsletter'],
                  'customers_password' => $data['password'],
                  'customers_groups_id' => ( $data['customers_groups_id'] == '' ) ? NULL : $data['customers_groups_id'], 
                  'customers_status' => $data['status']);
    
    if (is_numeric($id))
    {
      $data['date_account_last_modified'] = date('Y-m-d');
      
      $this->db->update('customers', $data, array('customers_id' => $id));
    }
    else
    {
      $data['number_of_logons'] = 0;
      $data['date_account_created'] = date('Y-m-d');
      
      $this->db->insert('customers', $data);
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      if ( !empty($data['customers_password']) )
      {
        $customer_id = ( !empty($id) ) ? $id : $this->db->insert_id();
        
        $this->db->update('customers', array('customers_password' => $this->encrypt->encode(trim($data['customers_password']))), array('customers_id' => $customer_id));
      }
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    else
    {
      $this->db->trans_rollback();
      
      return FALSE;
    }
  }
  
  public function get_data($id, $key = NULL)
  {
    $Qcustomer = $this->db
    ->select('c.*, cg.*, ab.*')
    ->from('customers c')
    ->join('address_book ab', 'c.customers_default_address_id = ab.address_book_id and c.customers_id = ab.customers_id', 'left')
    ->join('customers_groups_description cg', 'c.customers_groups_id = cg.customers_groups_id and cg.language_id = ' . lang_id(), 'left')
    ->where('c.customers_id', $id)
    ->get();
    
    $data = $Qcustomer->row_array();
    
    $Qreviews = $this->db
    ->select('count(*) as total')
    ->from('reviews')
    ->where('customers_id', $id)
    ->get();
    
    $total_reviews = $Qreviews->row_array();
    $data['total_reviews'] = $total_reviews['total'];
    
    $Qreviews->free_result();
    $Qcustomer->free_result();
    
    $data['customers_full_name'] = $data['customers_firstname'] . ' ' . $data['customers_lastname'];
    
    if ( !empty($key) ) 
    {
      return $data[$key];
    }
    
    return $data;
  }
  
  public function get_addressbook_data($customers_id, $address_book_id = NULL)
  {
    $this->db
    ->select('ab.address_book_id, ab.entry_gender as gender, ab.entry_firstname as firstname, ab.entry_lastname as lastname, ab.entry_company as company, ab.entry_street_address as street_address, ab.entry_suburb as suburb, ab.entry_city as city, ab.entry_postcode as postcode, ab.entry_state as state, ab.entry_zone_id as zone_id, ab.entry_country_id as country_id, ab.entry_telephone as telephone_number, ab.entry_fax as fax_number, z.zone_code as zone_code, c.countries_name as country_title')
    ->from('address_book ab')
    ->join('zones z', 'ab.entry_zone_id = z.zone_id', 'left')
    ->join('countries c', 'ab.entry_country_id = c.countries_id', 'left')
    ->where('ab.customers_id', $customers_id);
    
    if (is_numeric($address_book_id))
    {
      $this->db->where('ab.address_book_id', $address_book_id);
    }
    
    $Qab = $this->db->get();
    
   if ( is_numeric($address_book_id) ) 
   {
      $data = $Qab->row_array();

      $Qab->free_result();

      return $data;
    }
    
    return $Qab->result_array();
  }
  
  public function delete_address($id, $customer_id = null)
  {
    $conditions = array('address_book_id' => $id);
  
    if ( !empty($customer_id) ) {
      $conditions['customers_id'] = $customer_id;
    }
    
    $this->db->delete('address_book', $conditions);
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_state_zones($country_id)
  {
    $Qcheck = $this->db
    ->select('zone_id')
    ->from('zones')
    ->where('zone_country_id', $country_id)
    ->get();
    
    return $Qcheck->num_rows();
  }
  
  public function get_zones($country_id, $zone_code)
  {
    $Qzone = $this->db
    ->select('zone_id')
    ->from('zones')
    ->where(array('zone_country_id' => $country_id, 'zone_code' => $zone_code))
    ->get();
    
    return $Qzone->result_array();
  }
  
  public function get_zone_likes($country_id, $zone_name)
  {
    $Qzone = $this->db
    ->select('zone_id')
    ->from('zones')
    ->where('zone_country_id', $country_id)
    ->like('zone_name', $zone_name, 'after')
    ->get();
    
    return $Qzone->result_array();
  }
  
  public function save_address($id = NULL, $data)
  {
    $this->db->trans_begin();
    
    $Qcustomer = $this->db
    ->select('customers_gender, customers_firstname, customers_lastname, customers_email_address, customers_default_address_id')
    ->from('customers')
    ->where('customers_id', $data['customer_id'])
    ->get();
      
    $customer = $Qcustomer->row_array();
    
    $info = array('entry_gender' => $data['gender'], 
                  'entry_company' => $data['company'], 
                  'entry_firstname' => $data['firstname'], 
                  'entry_lastname' => $data['lastname'], 
                  'entry_street_address' => $data['street_address'], 
                  'entry_suburb' => $data['suburb'], 
                  'entry_postcode' => $data['postcode'], 
                  'entry_city' => $data['city'], 
                  'entry_state' => $data['state'], 
                  'entry_country_id' => $data['country_id'], 
                  'entry_zone_id' => $data['zone_id'], 
                  'entry_telephone' => $data['telephone'], 
                  'entry_fax' => $data['fax']);
                  
    
    if (is_numeric($id))
    {
      $this->db->update('address_book', $info, array('address_book_id' => $id, 'customers_id' => $data['customer_id']));
    }
    else
    {
      $info['customers_id'] = $data['customer_id'];
      $this->db->insert('address_book', $info);
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      if ( ($customer['customers_default_address_id'] < 1) || ($data['primary'] === true) )
      {
        $address_book_id = ( is_numeric($id) ? $id : $this->db->insert_id() );
        
        $Qupdate = $this->db->update('customers', array('customers_default_address_id' => $address_book_id), array('customers_id' => $data['customer_id']));
      }
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      return TRUE;
    }
    else
    {
      $this->db->trans_rollback();
      
      return FALSE;
    }
  }
} 

/* End of file customers_model.php */
/* Location: ./system/modules/customers/models/customers_model.php */