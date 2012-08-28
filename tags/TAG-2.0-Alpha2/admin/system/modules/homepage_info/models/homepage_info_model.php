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
 * @filesource ./system/modules/homepage_info/models/homepage_info_model.php
 */

class Homepage_Info_Model extends CI_Model
{
  public function save_data($data)
  {
    $error = FALSE;
    
    $this->db->trans_begin();
    
    foreach($data['page_title'] as $key => $value)
    {
      $this->db->update('configuration', array('configuration_value' => $value), array('configuration_key' => 'HOME_PAGE_TITLE_' . $key));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
        break;
      }
    }
    
    if ($error === FALSE)
    {
      foreach($data['keywords'] as $key => $value)
      {
        $this->db->update('configuration', array('configuration_value' => $value), array('configuration_key' => 'HOME_META_KEYWORD_' . $key));
        
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
          break;
        }
      }
    }
    
    if ($error === FALSE)
    {
      foreach($data['descriptions'] as $key => $value)
      {
        $this->db->update('configuration', array('configuration_value' => $value), array('configuration_key' => 'HOME_META_DESCRIPTION_' . $key));
        
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
          break;
        }
      }
    }
    
    if ($error === FALSE)
    {
      foreach($data['index_text'] as $languages_id => $value)
      {
        $this->db->update('languages_definitions', array('definition_value' => $value), array('definition_key' => 'index_text', 'languages_id' => $languages_id));
        
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
      
      if ($this->cache->get('configuration'))
      {
        $this->cache->delete('configuration');
      }
      
      foreach(lang_get_all() as $l)
      {
        if ($this->cache->get('languages-' . $l['code'] . '-index'))
        {
          $this->cache->delete('languages-' . $l['code'] . '-index');
        }
      }
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function get_data()
  {
    $data = array();
    $clear_cache = FALSE;
    
    foreach(lang_get_all() as $l)
    {
      $name = $l['name'];
      $code = strtoupper($l['code']);
      
      //check page title for language
      if (!defined('HOME_PAGE_TITLE_' . $code))
      {
        $this->db->insert('configuration', array('configuration_title' => 'Homepage Page Title For ' . $name, 
                                                 'configuration_key' => 'HOME_PAGE_TITLE_' . $code, 
                                                 'configuration_value' => '', 
                                                 'configuration_description' => 'the page title for the front page', 
                                                 'configuration_group_id' => '6', 
                                                 'sort_order' => '0', 
                                                 'date_added' => date('Y-m-d H:i:s')));
        
        define('HOME_PAGE_TITLE_' . $code, '');
        
        $clear_cache = TRUE;
      }
      
      //check meta keywords for language
      if (!defined('HOME_META_KEYWORD_' . $code))
      {
        $this->db->insert('configuration', array('configuration_title' => 'Homepage Meta Keywords For ' . $name, 
                                                 'configuration_key' => 'HOME_META_KEYWORD_' . $code, 
                                                 'configuration_value' => '', 
                                                 'configuration_description' => 'the meta keywords for the front page', 
                                                 'configuration_group_id' => '6', 
                                                 'sort_order' => '0', 
                                                 'date_added' => date('Y-m-d H:i:s')));
        
        define('HOME_META_KEYWORD_' . $code, '');
          
        $clear_cache = TRUE;
      }
      
      //check meta description for language
      if (!defined('HOME_META_DESCRIPTION_' . $code))
      {
        $this->db->insert('configuration', array('configuration_title' => 'Homepage Meta Description For ' . $name, 
                                                 'configuration_key' => 'HOME_META_DESCRIPTION_' . $code, 
                                                 'configuration_value' => '', 
                                                 'configuration_description' => 'the meta description for the front page', 
                                                 'configuration_group_id' => '6', 
                                                 'sort_order' => '0', 
                                                 'date_added' => date('Y-m-d H:i:s')));
        
        define('HOME_META_DESCRIPTION_' . $code, '');
          
        $clear_cache = TRUE;     
      }
      
      $Qhomepage = $this->db
      ->select('*')
      ->from('languages_definitions')
      ->where(array('definition_key' => 'index_text', 'languages_id' => $l['id']))
      ->get();
      
      if ($Qhomepage->num_rows() > 0)
      {
        foreach($Qhomepage->result_array() as $homepage)
        {
          $data['index_text[' . $l['id'] . ']'] = $homepage['definition_value'];
        }
      }
      
      $data['HOME_PAGE_TITLE[' . $code . ']'] = constant('HOME_PAGE_TITLE_' . $code);
      $data['HOME_META_KEYWORD[' . $code . ']'] = constant('HOME_META_KEYWORD_' . $code);
      $data['HOME_META_DESCRIPTION[' . $code . ']'] = constant('HOME_META_DESCRIPTION_' . $code);
    }
    
    if ($clear_cache == true)
    {
      if ($this->cache->get('configuration'))
      {
        $this->cache->delete('configuration');
      }
    }
    
    return $data;
  }
}


/* End of file homepage_info_model.php */
/* Location: ./system/modules/homepage_info/models/homepage_info_model.php */