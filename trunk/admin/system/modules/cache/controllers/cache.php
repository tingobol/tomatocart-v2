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
 * @filesource .system/modules/cache/controllers/cache.php
 */

class Cache extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('cache_grid');
  }
  
  public function list_cache()
  {
    $this->load->helper('directory');
    $this->load->helper('date');
    
    $cache_map = directory_map(ROOTPATH . '/local/cache');
    
    $records = array();
    $cached_files = array();
    
    if (!empty($cache_map))
    {
      foreach($cache_map as $cache)
      {
        $last_modified = filemtime(ROOTPATH . '/local/cache/' . $cache);
        
        if (strpos($cache, '-') !== FALSE)
        {
          $code = substr($cache, 0, strpos($cache, '-'));
        }
        else
        {
          $code = $cache;
        }
        
        if(isset($cached_files[$code]))
        {
          $cached_files[$code]['total']++;
          
          if ($last_modified > $cached_files[$code]['last_modified'])
          {
            $cached_files[$code]['last_modified'] = $last_modified;
          }
        }
        else
        {
          $cached_files[$code] = array('total' => 1, 
                                       'last_modified' => $last_modified);
        } 
        
        $records[] = array('code' => $code, 
                           'total' => $cached_files[$code]['total'], 
                           'last_modified' => mdate('%Y/%m/%d %H:%i:%s', $cached_files[$code]['last_modified']));
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function delete_cache()
  {
    $this->load->helper('directory');
    
    $cache_map = directory_map(ROOTPATH . '/local/cache');
    
    foreach($cache_map as $cache)
    {
      if ($cache == $this->input->post('block') || substr($cache, 0, strpos($cache, '-')) == $this->input->post('block'))  
      {
        @unlink(ROOTPATH . '/local/cache/' . $cache);
      } 
    } 
    
    return array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
  }
  
  public function delete_caches()
  {
    $this->load->helper('directory');
    
    $cache_codes = json_decode($this->input->post('batch'));
    
    $cache_map = directory_map(ROOTPATH . '/local/cache');
    
    foreach($cache_map as $cache)
    {
      if (in_array($cache, $cache_codes) || in_array(substr($cache, 0, strpos($cache, '-')), $cache_codes))  
      {
        @unlink(ROOTPATH . '/local/cache/' . $cache);
      } 
    }
    
    return array('success' => TRUE ,'feedback' => lang('ms_success_action_performed'));
  }
}

/* End of file cache.php */
/* Location: ./system/modules/cache/controllers/cache.php */