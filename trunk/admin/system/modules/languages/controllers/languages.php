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
 * @filesource ./system/modules/languages/controllers/languages.php
 */

class Languages extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('lang_model');
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('languages_grid');
    $this->load->view('translations_edit_grid');
    $this->load->view('modules_tree_panel');
    $this->load->view('languages_add_dialog');
    $this->load->view('languages_edit_dialog');
    $this->load->view('languages_export_dialog');
    $this->load->view('translations_dialog');
    $this->load->view('translation_edit_dialog');
    $this->load->view('translation_add_dialog');
    $this->load->view('languages_upload_dialog');
  }
  
  public function list_languages()
  {
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $languages = $this->lang_model->get_languages($start, $limit);
    
    $records = array();
    if (!empty($languages))
    {
      foreach($languages as $language)
      {
        $total_definitions = $this->lang_model->get_total_definitions($language['languages_id']);
        
        $languages_name = $language['name'];
        
        if ($language['code'] == DEFAULT_LANGUAGE)
        {
          $languages_name .= ' (' . lang('default_entry') . ')';
        }
        
        $records[] = array(
          'languages_id' => $language['languages_id'],
          'code' =>  $language['code'],
          'total_definitions' => $total_definitions,
          'languages_name' => $languages_name,
          'languages_flag' => show_image($language['code'])
        );
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->lang_model->get_total(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function get_currencies()
  {
    $this->load->library('currencies');
    
    $records = array();
    foreach($this->currencies->get_data() as $currency)
    {
      $records[] = array('currencies_id' => $currency['id'],
                         'text' => $currency['title']);
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function get_parent_language()
  {
    $records =  array(array('parent_id' => '0', 'text' => lang('none')));

    foreach(lang_get_all() as $l)
    {
      if ($l['id'] != $this->input->post('languages_id')) 
      {
        $records[] = array('parent_id' => $l['id'], 'text' => $l['name'] . ' (' . $l['code'] . ')');
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function load_language()
  {
    $data = $this->lang_model->get_data($this->input->post('languages_id'));
    $data['default'] = ($data['code'] == DEFAULT_LANGUAGE) ? TRUE : FALSE;
    
    return array('success' => TRUE, 'data' => $data);
  }
  
  public function save_language()
  {
    $this->load->driver('cache', array('adapter' => 'file'));
    
    $data = array('name' => $this->input->post('name'),
                  'code' => $this->input->post('code'),
                  'locale' => $this->input->post('locale'),
                  'charset' => $this->input->post('charset'),
                  'date_format_short' => $this->input->post('date_format_short'),
                  'date_format_long' => $this->input->post('date_format_long'),
                  'time_format' => $this->input->post('time_format'),
                  'text_direction' => $this->input->post('text_id'),
                  'currencies_id' => $this->input->post('currencies_id'),
                  'numeric_separator_decimal' => $this->input->post('numeric_separator_decimal'),
                  'numeric_separator_thousands' => $this->input->post('numeric_separator_thousands'),
                  'parent_id' => $this->input->post('parent_id'),
                  'sort_order' => $this->input->post('sort_order'));
    
    if ($this->lang_model->update($this->input->post('languages_id'), $data, $this->input->post('default') == 'on'))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function get_languages()
  {
    $this->load->helper('directory');
    
    $languages_map = directory_map(ROOTPATH . '/system/tomatocart/language');
    
    $records = array();
    if (!empty($languages_map))
    {
      foreach($languages_map as $language)
      {
        if (!is_array($language)) {
          $records[] = array('id' => substr($language, 0, strrpos($language, '.')), 
                             'text' => substr($language, 0, strrpos($language, '.')));
        }
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function import_language()
  {
    $this->load->library('currencies');
    $this->load->library('xml');
    $this->load->library('directory_listing', array());
    $this->load->driver('cache', array('adapter' => 'file'));
    
    if ($this->lang_model->import($this->input->post('languages_id'), $this->input->post('import_type')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_language()
  {
    $error = FALSE;
    $feedback = array();
    
    if ($this->input->post('code') == DEFAULT_LANGUAGE)
    {
      $error = TRUE;
      $feedback[] = lang('introduction_delete_language_invalid');
    }
    
    if ($error === FALSE)
    {
      if ($this->lang_model->remove($this->input->post('languages_id')))
      {
        $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
      }
      else
      {
        $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
      }
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
    }
    
    return $response;
  }
  
  public function delete_languages()
  {
    $error = FALSE;
    $feedback = array();
    
    $batch = json_decode($this->input->post('batch'));
    
    $codes = $this->lang_model->check_codes($batch);
    
    if (!empty($codes))
    {
      foreach($codes as $code)
      {
        if ($code == DEFAULT_LANGUAGE)
        {
          $error = TRUE;
          
          $feedback[] = lang('introduction_delete_language_invalid');
          break;
        }
      }
    }
    
    if ($error === FALSE)
    {
      foreach($batch as $id)
      {
        if (!$this->lang_model->remove($id))
        {
          $error = TRUE;
          break;
        }
      }
      
      if ($error === FALSE)
      {
        $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
      }
      else
      {
        $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
      }
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
    }
    
    return $response;
  }
  
  public function get_groups()
  {
    $groups = $this->lang_model->get_content_groups($this->input->get_post('languages_id'));
    
    $records = array();
    if (!empty($groups))
    {
      foreach($groups as $group)
      {
        $records[] = array('id' => $group['content_group'],
                           'text' => $group['content_group']);
      }
    }
    
    return array(EXT_JSON_READER_ROOT => $records);
  }
  
  public function export()
  {
    $this->load->library('currencies');
    $this->load->library('xml');
    
    $groups = explode(',', $this->input->get_post('export'));
    
    $this->lang_model->export($this->input->get_post('languages_id'), $groups, $this->input->get_post('include_data') == true);
  }
  
  public function list_translations()
  {
    $languages_id = $this->input->get_post('languages_id');
    $group = $this->input->get_post('group') ? $this->input->get_post('group') : 'general';
    
    $definitions = $this->lang_model->get_definitions($languages_id, $group, $this->input->get_post('search'));
    
    $records = array();
    if (!empty($definitions))
    {
      foreach($definitions as $definition)
      {
        $records[] = array('languages_definitions_id' => $definition['id'], 
                           'languages_id' => $languages_id, 
                           'definition_key' => $definition['definition_key'], 
                           'definition_value' => $definition['definition_value'], 
                           'content_group' => $group);
      }
    }
    
    return array('total' => sizeof($records), 'records' => $records);
  }
  
  public function list_translation_groups()
  {
    $groups = $this->lang_model->get_content_groups($this->input->get_post('languages_id'));
    
    $records = array();
    if (!empty($groups))
    {
      foreach($groups as $group)
      {
         $records[] = array('id' => $group['content_group'], 
                            'text' => $group['content_group'],
                            'leaf' => TRUE);
      }
    }
    
    return $records;
  }
  
  public function update_translation()
  {
    $value = rtrim($this->input->post('definition_value'));
    
    if ($this->lang_model->save_definition($this->input->post('languages_id'), $this->input->post('group'), $this->input->post('definition_key'), $value))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function delete_translation()
  {
    if ($this->lang_model->delete_definition($this->input->post('languages_definitions_id')))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function add_translation()
  {
    $data = array('languages_id' => $this->input->post('languages_id'),
                  'content_group' => $this->input->post('definition_group'),
                  'definition_key' => $this->input->post('definition_key'),
                  'definition_value' => rtrim($this->input->post('definition_value')));
    
    if ($this->lang_model->add_definition($data))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
  
  public function upload_language()
  {
    $this->load->library('currencies');
    $this->load->library('upload');
    $this->load->helper('directory');
    
    $error = FALSE;
    $feedback = array();
    
    $tmp_path =  APPFRONTPATH . 'cache/language/' . time();
    
    if (!is_dir(APPFRONTPATH . 'cache/language'))
    {
      if (!mkdir(APPFRONTPATH . 'cache/language', 0777))
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE && mkdir($tmp_path, 0777))
    {
      $config = array('upload_path' => $tmp_path, 'allowed_types' => 'zip');
      $this->upload->initialize($config);
      
      if ($this->upload->do_upload('file'))
      {
        $upload_data = $this->upload->data();
        
        $this->load->library('pclzip', $upload_data['full_path']);
        
        if ($this->pclzip->extract(PCLZIP_OPT_PATH, $tmp_path) == 0)
        {
          $error = TRUE;
          $feedback[] = lang('ms_error_wrong_zip_file_format');
        }
      }
      else
      {
        $error = TRUE;
        $feedback[] = $this->upload->display_errors();
      }
    }
    else
    {
      $error = TRUE;
      $feedback[] = sprintf(lang('ms_error_creating_directory_failed'), APPFRONTPATH . 'cache');
    }
    
    if ($error === FALSE)
    {
      $this->load->library('directory_listing');
      $this->directory_listing->setIncludeDirectories(TRUE);
      $this->directory_listing->setIncludeFiles(FALSE);
      $files = $this->directory_listing->getFiles();
      
      $code = NULL;
      foreach($files as $file)
      {
        if (is_dir($tmp_path . '/' . $file['name']) && is_dir($tmp_path . '/' . $file['name'] . '/admin'))
        {
          $code = $file['name'];
          
          break;
        }
      }
      
      if ($code != NULL)
      {
        dircopy($tmp_path . '/' . $code . "/language", APPFRONTPATH . 'language');
        dircopy($tmp_path . '/' . $code . "/admin/language", APPPATH . 'language');
        
        @unlink($tmp_path);
      }
      else
      {
        $error = TRUE;
        $feedback[] = lang('ms_error_wrong_language_package');
      }
    }
    
    if ($error === FALSE)
    {
      if ($this->lang_model->import($code, 'replace'))
      {
        $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
      }
      else
      {
        $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
      }
    }
    else
    {
      $response = array('success' => false, 'feedback' => $osC_Language->get('ms_error_action_not_performed') . "<br />" . implode("<br />", $feedback));
    }
    
    header('Content-Type: text/html');
    
    return $response;
  }
}

/* End of file languages.php */
/* Location: ./system/modules/languages/controllers/languages.php */