<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @license   http://www.tomatocart.com/doc-license
 * @link    http://www.tomatocart.com
 */

class Access_Model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function get_user_levels($admin_id)
  {
    $modules = array();
    
    $Qaccess = $this->db
    ->select('module')
    ->from('administrators_access')
    ->where('administrators_id', $admin_id)
    ->get();
    
    if ($Qaccess->num_rows() > 0)
    {
      foreach ($Qaccess->result_array() as $access)
      {
        $modules[]= $access['module'];
      }
    }
    
    if ( in_array('*', $modules) )
    {
      $modules = array();
      
      $access_DirectoryListing = directory_map(APPPATH . 'modules/access', 1);
      
      foreach($access_DirectoryListing as $file)
      {
        $modules[] = substr($file, 0, strrpos($file, '.'));
      }
    }
    
    return $modules;
  }
}
