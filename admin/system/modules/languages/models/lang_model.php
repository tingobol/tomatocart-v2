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
 * @filesource ./system/modules/languages/models/languages_model.php
 */

class Lang_Model extends CI_Model
{
  public function get_data($id, $key = null)
  {
    $Qlanguage = $this->db
    ->select('*')
    ->from('languages')
    ->where('languages_id', $id)
    ->get();
    
    $result = $Qlanguage->row_array();
    $Qlanguage->free_result();
    
    if (empty($key))
    {
      return $result;
    }
    else
    {
      return $result[$key];
    }
  }
  
  public function get_languages($start, $limit)
  {
    $Qlanguages = $this->db
    ->select('*')
    ->from('languages')
    ->order_by('sort_order, name')
    ->limit($limit, $start > 0 ? $start - 1 : $start)
    ->get();
    
    return $Qlanguages->result_array();
  }
  
  public function update($id, $language, $default = FALSE)
  {
    $this->db->trans_begin();
    
    $this->db->update('languages', array('name' => $language['name'], 
                                         'code' => $language['code'], 
                                         'locale' => $language['locale'], 
                                         'charset' => $language['charset'], 
                                         'date_format_short' => $language['date_format_short'], 
                                         'date_format_long' => $language['date_format_long'], 
                                         'time_format' => $language['time_format'], 
                                         'text_direction' => $language['text_direction'], 
                                         'currencies_id' => $language['currencies_id'], 
                                         'numeric_separator_decimal' => $language['numeric_separator_decimal'], 
                                         'numeric_separator_thousands' => $language['numeric_separator_thousands'], 
                                         'parent_id' => $language['parent_id'], 
                                         'sort_order' => $language['sort_order']), array('languages_id' => $id));
    
    if ($this->db->trans_status() === TRUE)
    {
      if ($default === TRUE)
      {
        $this->db->update('configuration', array('configuration_value' => $language['code']), array('configuration_key' => 'DEFAULT_LANGUAGE'));
        
        if ($this->db->affected_rows() > 0)
        {
          if ($this->cache->get('configuration'))
          {
            $this->cache->delete('configuration');
          }
        }
      }
    }
    
    if ($this->db->trans_status() === TRUE)
    {
      $this->db->trans_commit();
      
      if ($this->cache->get('languages'))
      {
        $this->cache->delete('languages');
      }
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function import($file, $type)
  {
    $xml_path = APPFRONTPATH . 'language/' . $file . '.xml';
    
    $languages_definitions = array();
    if (file_exists($xml_path))
    {
      if ($this->xml->load($xml_path))
      {
        $language_definitions = $this->xml->parse();
        
        $language = array('name' => $language_definitions['language'][0]['data'][0]['title'][0],
                          'code' => $language_definitions['language'][0]['data'][0]['code'][0],
                          'locale' => $language_definitions['language'][0]['data'][0]['locale'][0],
                          'charset' => $language_definitions['language'][0]['data'][0]['character_set'][0],
                          'date_format_short' => $language_definitions['language'][0]['data'][0]['date_format_short'][0],
                          'date_format_long' => $language_definitions['language'][0]['data'][0]['date_format_long'][0],
                          'time_format' => $language_definitions['language'][0]['data'][0]['time_format'][0],
                          'text_direction' => $language_definitions['language'][0]['data'][0]['text_direction'][0],
                          'numeric_separator_decimal' => $language_definitions['language'][0]['data'][0]['numerical_decimal_separator'][0],
                          'numeric_separator_thousands' => $language_definitions['language'][0]['data'][0]['numerical_thousands_separator'][0],
                          'parent_id' => 0
                         );
                         
        $currency = $language_definitions['language'][0]['data'][0]['default_currency'][0];
        if (!$this->currencies->exists($currency))
        {
          $currency = DEFAULT_CURRENCY;
        }
        $language['currencies_id'] = $this->currencies->get_id($currency);
        
        $parent_language_code = $language_definitions['language'][0]['data'][0]['parent_language_code'][0];
        
        if (!empty($parent_language_code))
        {
          $Qlanguage = $this->db
          ->select('languages_id')
          ->from('languages')
          ->where('code', $parent_language_code)
          ->get();
          
          if ($Qlanguage->num_rows() === 1)
          {
            $parent_lang = $Qlanguage->row_array();
            $Qlanguage->free_result();
            
            $language['parent_id'] = $parent_lang['languages_id'];
          }
        }
        
        $definitions = array();
        if (isset($language_definitions['language'][0]['definitions'][0]['definition']))
        {
          $definitions = $language_definitions['language'][0]['definitions'][0]['definition'];
        }
        
        $tables = array();
        if (isset($language_definitions['language'][0]['tables'][0]['table']))
        {
          $tables = $language_definitions['language'][0]['tables'][0]['table'];
        }
        
        unset($language_definitions);
        
        $error = FALSE;
        $add_category_and_product_placeholders = TRUE;
        
        $this->db->trans_begin();
        
        $Qcheck = $this->db
        ->select('languages_id')
        ->from('languages')
        ->where('code', $language['code'])
        ->get();
        
        if ($Qcheck->num_rows() === 1)
        {
          $lang = $Qcheck->row_array();
          
          $language_id = $lang['languages_id'];
          
          $add_category_and_product_placeholders = FALSE;
          
          $this->db->update('languages', $language, array('languages_id' => $language_id));
        }
        else
        {
          $this->db->insert('languages', $language);
        }
        
        if ($this->db->trans_status() === FALSE)
        {
          $error = TRUE;
        }
        else
        {
          if ($Qcheck->num_rows() != 1)
          {
            $language_id = $this->db->insert_id();
          }
          
          $Qcheck->free_result();
          
          $default_language_id = $this->get_data($this->get_id(DEFAULT_LANGUAGE), 'languages_id');
          
          if ($type == 'replace')
          {
            $this->db->delete('languages_definitions', array('languages_id' => $language_id));
            
            if ($this->db->trans_status() === FALSE)
            {
              $error = TRUE;
            }
          }
        }
        
        //Handle languages definitions
        if ($error === FALSE)
        {
          $this->directory_listing->setDirectory(APPFRONTPATH . 'language/' . $file);
          $this->directory_listing->setRecursive(TRUE);
          $this->directory_listing->setIncludeDirectories(FALSE);
          $this->directory_listing->setAddDirectoryToFilename(TRUE);
          $this->directory_listing->setCheckExtension('xml');
          
          foreach($this->directory_listing->getFiles() as $sub_file)
          {
            $sub_definitions = $this->extract_definitions(APPFRONTPATH . 'language/' . $file . '/' . $sub_file['name']);
            
            foreach($sub_definitions as $definition)
            {
              $definitions[] = $definition;
            }
          }
          
          foreach($definitions as $definition)
          {
            $insert = FALSE;
            $update = FALSE;
            
            if ($type == 'replace')
            {
              $insert = TRUE;
            }
            else
            {
              $Qcheck = $this->db
              ->select('definition_key, content_group')
              ->from('languages_definitions')
              ->where(array('definition_key' => $definition['key'][0], 'languages_id' => $language_id, 'content_group' => $definition['group'][0]))
              ->get();
              
              if ($Qcheck->num_rows() > 0)
              {
                if ($type == 'update')
                {
                  $update = TRUE;
                }
              }
              else if ($type == 'add')
              {
                $insert = TRUE;
              }
            }
            
            if ($insert === TRUE || $update === TRUE)
            {
              if ($insert === TRUE)
              {
                $this->db->insert('languages_definitions', array('languages_id' => $language_id, 
                                                                 'content_group' => $definition['group'][0], 
                                                                 'definition_key' => $definition['key'][0], 
                                                                 'definition_value' => $definition['value'][0]));
              }
              else
              {
                $this->db->update('languages_definitions', array('content_group' => $definition['group'][0], 
                                                                 'definition_key' => $definition['key'][0], 
                                                                 'definition_value' => $definition['value'][0]), 
                                                           array('definition_key' => $definition['key'][0], 
                                                                 'content_group' => $definition['group'][0], 
                                                                 'languages_id' => $language_id));
              }
              
              if ($this->db->trans_status() === FALSE)
              {
                $error = TRUE;
                break;
              }
            }
          }
        }
        //End handle definitions
        
        //Handle Tables
        if ($error === FALSE && $add_category_and_product_placeholders === TRUE)
        {
          if (!empty($tables))
          {
            foreach($tables as $table)
            {
              $table_name = str_replace('toc_', '', $table['meta'][0]['name'][0]);
              $key_field = $table['meta'][0]['key_field'][0];
              $language_field = $table['meta'][0]['language_field'][0];
              
              $Qcheck = $this->db
              ->select('*')
              ->from($table_name)
              ->where($language_field, $default_language_id)
              ->get();
              
              if ($Qcheck->num_rows() > 0)
              {
                foreach($Qcheck->result_array() as $data)
                {
                  $data[$language_field] = $language_id;
                  $insert = FALSE;
                  
                  foreach($table['definition'] as $definition)
                  {
                    if ($data[$key_field] == $definition['key'][0])
                    {
                      $insert = TRUE;
                      foreach($definition as $key => $value)
                      {
                        if ($key != 'key' && array_key_exists($key, $data))
                        {
                          $data[$key] = $this->db->escape_str($value[0]);
                        }
                      }
                    }
                  }
                  
                  if ($insert === TRUE)
                  {
                    $this->db->insert($table_name, $data);
                    
                    if ($this->db->trans_status() === FALSE)
                    {
                      $error = TRUE;
                    }
                  }
                }
              }
            }
          }
        }
        //End handle Tables
        
        //handle the database tables
        if ($default_language_id != $language_id)
        {
          if ($error === FALSE)
          {
            $Qcategories_desc = $this->db
            ->select('categories_id, categories_name, categories_url, categories_page_title, categories_meta_keywords, categories_meta_description')
            ->from('categories_description')
            ->where('language_id', $default_language_id)
            ->get();
            
            $categories_desc = $Qcategories_desc->result_array();
            $Qcategories_desc->free_result();
            
            if (!empty($categories_desc))
            {
              foreach($categories_desc as $category_desc)
              {
                $this->db->insert('categories_description', array('categories_id' => $category_desc['categories_id'], 
                                                                  'language_id' => $language_id, 
                                                                  'categories_name' => $category_desc['categories_name'], 
                                                                  'categories_url' => $category_desc['categories_url'], 
                                                                  'categories_page_title' => $category_desc['categories_page_title'], 
                                                                  'categories_meta_keywords' => $category_desc['categories_meta_keywords'], 
                                                                  'categories_meta_description' => $category_desc['categories_meta_description']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qproducts_desc = $this->db
            ->select('products_id, products_name, products_description, products_keyword, products_tags, products_url, products_friendly_url, products_page_title, products_meta_keywords, products_meta_description, products_viewed')
            ->from('products_description')
            ->where('language_id', $default_language_id)
            ->get();
            
            $products_desc = $Qproducts_desc->result_array();
            $Qproducts_desc->free_result();
            
            if (!empty($products_desc))
            {
              foreach($products_desc as $product_desc)
              {
                $this->db->insert('products_description', array('products_id' => $product_desc['products_id'], 
                                                                'language_id' => $language_id, 
                                                                'products_name' => $product_desc['products_name'], 
                                                                'products_description' => $product_desc['products_description'], 
                                                                'products_keyword' => $product_desc['products_keyword'], 
                                                                'products_tags' => $product_desc['products_tags'], 
                                                                'products_url' => $product_desc['products_url'], 
                                                                'products_friendly_url' => $product_desc['products_friendly_url'], 
                                                                'products_page_title' => $product_desc['products_page_title'], 
                                                                'products_meta_keywords' => $product_desc['products_meta_keywords'], 
                                                                'products_meta_description' => $product_desc['products_meta_description'], 
                                                                'products_viewed' => $product_desc['products_viewed']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qcustomization_fields_desc = $this->db
            ->select('customization_fields_id, languages_id, name')
            ->from('customization_fields_description')
            ->where('languages_id', $default_language_id)
            ->get();
            
            $customization_fields_desc = $Qcustomization_fields_desc->result_array();
            $Qcustomization_fields_desc->free_result();
            
            if (!empty($customization_fields_desc))
            {
              foreach($customization_fields_desc as $field_desc)
              {
                $this->db->insert('customization_fields_description', array('customization_fields_id' => $field_desc['customization_fields_id'], 
                                                                            'languages_id' => $language_id, 'name' => $field_desc['name']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qvariants = $this->db
            ->select('products_variants_groups_id, products_variants_groups_name')
            ->from('products_variants_groups')
            ->where('language_id', $default_language_id)
            ->get();
            
            $variants = $Qvariants->result_array();
            $Qvariants->free_result();
            
            if (!empty($variants))
            {
              foreach($variants as $variant)
              {
                $this->db->insert('products_variants_groups', array('products_variants_groups_id' => $variant['products_variants_groups_id'], 
                                                                    'language_id' => $language_id, 
                                                                    'products_variants_groups_name' => $variant['products_variants_groups_name']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qvalues = $this->db
            ->select('products_variants_values_id, products_variants_values_name')
            ->from('products_variants_values')
            ->where('language_id', $default_language_id)
            ->get();
            
            $values = $Qvalues->result_array();
            $Qvalues->free_result();
            
            if (!empty($values))
            {
              foreach($values as $value)
              {
                $this->db->insert('products_variants_values', array('products_variants_values_id' => $value['products_variants_values_id'], 
                                                                    'language_id' => $language_id, 
                                                                    'products_variants_values_name' => $value['products_variants_values_name']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qmanufacturers = $this->db
            ->select('manufacturers_id, manufacturers_url, manufacturers_friendly_url')
            ->from('manufacturers_info')
            ->where('languages_id', $default_language_id)
            ->get();
            
            $manufacturers = $Qmanufacturers->result_array();
            $Qmanufacturers->free_result();
            
            if (!empty($manufacturers))
            {
              foreach($manufacturers as $manufacturer)
              {
                $this->db->insert('manufacturers_info', array('manufacturers_id' => $manufacturer['manufacturers_id'], 
                                                              'languages_id' => $language_id, 
                                                              'manufacturers_url' => $manufacturer['manufacturers_url'], 
                                                              'manufacturers_friendly_url' => $manufacturer['manufacturers_friendly_url']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qimages = $this->db
            ->select('image_id, description, image, image_url, sort_order, status')
            ->from('slide_images')
            ->where('language_id', $default_language_id)
            ->get();
            
            $images = $Qimages->result_array();
            $Qimages->free_result();
            
            foreach($images as $image)
            {
              $this->db->insert('slide_images', array('image_id' => $image['image_id'], 
                                                      'language_id' => $language_id, 
                                                      'description' => $image['description'], 
                                                      'image' => $image['image'], 
                                                      'image_url' => $image['image_url'], 
                                                      'sort_order' => $image['sort_order'], 
                                                      'status' => $image['status']));
              
              if ($this->db->trans_status() === FALSE)
              {
                $error = TRUE;
                break;
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qattributes = $this->db
            ->select('products_attributes_values_id, products_attributes_groups_id, name, module, value, status, sort_order')
            ->from('products_attributes_values')
            ->where('language_id', $default_language_id)
            ->get();
            
            $attributes = $Qattributes->result_array();
            $Qattributes->free_result();
            
            if (!empty($attributes))
            {
              foreach($attributes as $attribute)
              {
                $this->db->insert('products_attributes_values', array('products_attributes_values_id' => $attribute['products_attributes_values_id'], 
                                                                      'products_attributes_groups_id' => $attribute['products_attributes_groups_id'], 
                                                                      'language_id' => $language_id, 
                                                                      'name' => $attribute['name'], 
                                                                      'module' => $attribute['module'], 
                                                                      'value' => $attribute['value'], 
                                                                      'status' => $attribute['status'], 
                                                                      'sort_order' => $attribute['sort_order']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qattributes = $this->db
            ->select('products_id, products_attributes_values_id, value')
            ->from('products_attributes')
            ->where('language_id', $default_language_id)
            ->get();
            
            $attributes = $Qattributes->result_array();
            $Qattributes->free_result();
            
            if (!empty($attributes))
            {
              foreach($attributes as $attribute)
              {
                $this->db->insert('products_attributes', array('products_id' => $attribute['products_id'], 
                                                               'products_attributes_values_id' => $attribute['products_attributes_values_id'], 
                                                               'value' => $attribute['value'], 
                                                               'language_id' => $language_id));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qfaqs = $this->db
            ->select('faqs_id, faqs_question, faqs_url, faqs_answer')
            ->from('faqs_description')
            ->where('language_id', $default_language_id)
            ->get();
            
            $faqs = $Qfaqs->result_array();
            $Qfaqs->free_result();
            
            if (!empty($faqs))
            {
              foreach($faqs as $fag)
              {
                $this->db->insert('faqs_description', array('faqs_id' => $faq['faqs_id'], 
                                                            'language_id' => $language_id, 
                                                            'faqs_question' => $faq['faqs_question'], 
                                                            'faqs_answer' => $faq['faqs_answer'], 
                                                            'faqs_url' => $faq['faqs_url']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qcoupons = $this->db
            ->select('coupons_id, coupons_name, coupons_description')
            ->from('coupons_description')
            ->where('language_id', $default_language_id)
            ->get();
            
            $coupons = $Qcoupons->result_array();
            $Qcoupons->free_result();
            
            if (!empty($coupons))
            {
              foreach($coupons as $coupon)
              {
                $this->db->insert('coupons_description', array('coupons_id' => $coupon['coupons_id'], 
                                                               'language_id' => $language_id, 
                                                               'coupons_name' => $coupon['coupons_name'], 
                                                               'coupons_description' => $coupon['coupons_description']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qarticles = $this->db
            ->select('articles_id, articles_name, articles_description, articles_url, articles_page_title, articles_meta_keywords, articles_meta_description')
            ->from('articles_description')
            ->where('language_id', $default_language_id)
            ->get();
            
            $articles = $Qarticles->result_array();
            $Qarticles->free_result();
            
            if (!empty($articles))
            {
              foreach($articles as $article)
              {
                $Qcheck = $this->db
                ->select('*')
                ->from('articles_description')
                ->where(array('articles_id' => $article['articles_id'], 'language_id' => $language_id))
                ->get();
                
                if ($Qcheck->num_rows() === 0)
                {
                  $this->db->insert('articles_description', array('articles_id' => $article['articles_id'], 
                                                                  'language_id' => $language_id, 
                                                                  'articles_name' => $article['articles_name'], 
                                                                  'articles_description' => $article['articles_description'], 
                                                                  'articles_url' => $article['articles_url'], 
                                                                  'articles_page_title' => $article['articles_page_title'], 
                                                                  'articles_meta_keywords' => $article['articles_meta_keywords'], 
                                                                  'articles_meta_description' => $article['articles_meta_description']));
                  
                  if ($this->db->trans_status() === FALSE)
                  {
                    $error = TRUE;
                    break;
                  }
                }
                
                $Qcheck->free_result();
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qarticle_categories = $this->db
            ->select('articles_categories_id, articles_categories_name, articles_categories_url, articles_categories_page_title, articles_categories_meta_keywords, articles_categories_meta_description')
            ->from('articles_categories_description')
            ->where('language_id', $default_language_id)
            ->get();
            
            $article_categories = $Qarticle_categories->result_array();
            $Qarticle_categories->free_result();
            
            if (!empty($article_categories))
            {
              foreach($article_categories as $category)
              {
                $this->db->insert('articles_categories_description', array('articles_categories_id' => $category['articles_categories_id'], 
                                                                           'language_id' => $language_id, 
                                                                           'articles_categories_name' => $category['articles_categories_name'], 
                                                                           'articles_categories_url' => $category['articles_categories_url'], 
                                                                           'articles_categories_page_title' => $category['articles_categories_page_title'], 
                                                                           'articles_categories_meta_keywords' => $category['articles_categories_meta_keywords'], 
                                                                           'articles_categories_meta_description' => $category['articles_categories_meta_description']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qgroups = $this->db
            ->select('customers_groups_id, customers_groups_name')
            ->from('customers_groups_description')
            ->where('language_id', $default_language_id)
            ->get();
            
            $groups = $Qgroups->result_array();
            $Qgroups->free_result();
            
            foreach($groups as $group)
            {
              $this->db->insert('customers_groups_description', array('customers_groups_id' => $group['customers_groups_id'], 
                                                                      'language_id' => $language_id, 
                                                                      'customers_groups_name' => $group['customers_groups_name']));
              
              if ($this->db->trans_status() === FALSE)
              {
                $error = TRUE;
                break;
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qratings = $this->db
            ->select('ratings_id, ratings_text')
            ->from('ratings_description')
            ->where('languages_id', $default_language_id)
            ->get();
            
            $ratings = $Qratings->result_array();
            $Qratings->free_result();
            
            foreach($ratings as $rating)
            {
              $this->db->insert('ratings_description', array('ratings_id' => $rating['ratings_id'], 
                                                             'languages_id' => $language_id, 
                                                             'ratings_text' => $rating['ratings_text']));
              
              if ($this->db->trans_status() === FALSE)
              {
                $error = TRUE;
                break;
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qpolls = $this->db
            ->select('polls_id, polls_title')
            ->from('polls_description')
            ->where('languages_id', $default_language_id)
            ->get();
            
            $polls = $Qpolls->result_array();
            $Qpolls->free_result();
            
            foreach($polls as $poll)
            {
              $this->db->insert('polls_description', array('polls_id' => $poll['polls_id'], 
                                                           'languages_id' => $language_id, 
                                                           'polls_title' => $poll['polls_title']));
              
              if ($this->db->trans_status() === FALSE)
              {
                $error = TRUE;
                break;
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qanswers = $this->db
            ->select('polls_answers_id, answers_title')
            ->from('polls_answers_description')
            ->where('languages_id', $default_language_id)
            ->get();
            
            $answers = $Qanswers->result_array();
            $Qanswers->free_result();
            
            if (!empty($answers))
            {
              foreach($answers as $answer)
              {
                $this->db->insert('polls_answers_description', array('polls_answers_id' => $answer['polls_answers_id'], 
                                                                     'languages_id' => $language_id, 
                                                                     'answers_title' => $answer['answers_title']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qattachments = $this->db
            ->select('attachments_id, attachments_name, attachments_description')
            ->from('products_attachments_description')
            ->where('languages_id', $default_language_id)
            ->get();
            
            $attachments = $Qattachments->result_array();
            $Qattachments->free_result();
            
            if (!empty($attachments))
            {
              foreach($attachments as $attachment)
              {
                $this->db->insert('products_attachments_description', array('attachments_id' => $attachment['attachments_id'], 
                                                                            'languages_id' => $language_id, 
                                                                            'attachments_name' => $attachment['attachments_name'], 
                                                                            'attachments_description' => $attachment['attachments_description']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
          
          if ($error === FALSE)
          {
            $Qdeparts = $this->db
            ->select('departments_id, departments_title, departments_description')
            ->from('departments_description')
            ->where('language_id', $default_language_id)
            ->get();
            
            $departs = $Qdeparts->result_array();
            $Qdeparts->free_result();
            
            if (!empty($departs))
            {
              foreach($departs as $depart)
              {
                $this->db->insert('departments_description', array('departments_id' => $depart['departments_id'], 
                                                                   'languages_id' => $language_id, 
                                                                   'departments_title' => $depart['departments_title'], 
                                                                   'departments_description' => $depart['departments_description']));
                
                if ($this->db->trans_status() === FALSE)
                {
                  $error = TRUE;
                  break;
                }
              }
            }
          }
        }
        //End handle the database tables
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->trans_commit();
      
      if ($this->cache->get('languages'))
      {
        $this->cache->delete('languages');
      }
      
      return TRUE;
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function &extract_definitions($xml)
  {
    $definitions = array();
    
    if (file_exists($xml))
    {
      if ($this->xml->load($xml))
      {
        $definitions = $this->xml->parse();
        
        $definitions = $definitions['language'][0]['definitions'][0]['definition'];
      }
    }
    
    return $definitions;
  }
  
  public function remove($id)
  {
    $error = FALSE;
    
    $Qcheck = $this->db
    ->select('code')
    ->from('languages')
    ->where('languages_id', $id)
    ->get();
    
    $check = $Qcheck->row_array();
    
    if ($check['code'] == DEFAULT_LANGUAGE)
    {
      $error = TRUE;
    }
    
    $this->db->trans_begin();
    
    if ($error === FALSE)
    {
      $this->db->delete('categories_description', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('customization_fields_description', array('languages_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('products_description', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('products_variants_groups', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('products_variants_values', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('manufacturers_info', array('languages_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('orders_status', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('orders_returns_status', array('languages_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('orders_transactions_status', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('products_images_groups', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('quantity_unit_classes', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('weight_classes', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('articles_description', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('articles_categories_description', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('coupons_description', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('customers_groups_description', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('email_templates_description', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('faqs_description', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('products_attributes', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('slide_images', array('language_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('languages', array('languages_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('languages_definitions', array('languages_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('ratings_description', array('languages_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('polls_description', array('languages_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('polls_answers_description', array('languages_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('products_attachments_description', array('languages_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->delete('departments_description', array('languages_id' => $id));
      
      if ($this->db->trans_status() === FALSE)
      {
        $error = TRUE;
      }
    }
    
    if ($error === FALSE)
    {
      $this->db->trans_commit();
      
      if ($this->cache->get('languages'))
      {
        $this->cache->delete('languages');
        
        return TRUE;
      }
    }
    
    $this->db->trans_rollback();
    
    return FALSE;
  }
  
  public function check_codes($languages_ids)
  {
    $Qcheck = $this->db
    ->select('code')
    ->from('languages')
    ->where_in('languages_id', $languages_ids)
    ->get();
    
    return $Qcheck->result_array();
  }
  
  public function get_content_groups($languages_id)
  {
    $Qgroups = $this->db
    ->select('content_group')
    ->from('languages_definitions')
    ->where('languages_id', $languages_id)
    ->group_by('content_group')
    ->get();
    
    return $Qgroups->result_array();
  }
  
  public function export($id, $groups, $include_language_data = TRUE)
  {
    $language = $this->get_data($id);
    
    $export_array = array();
    
    if ($include_language_data === TRUE) 
    {
      $export_array['data'] = array('title-CDATA' => $language['name'],
                                    'code-CDATA' => $language['code'],
                                    'locale-CDATA' => $language['locale'],
                                    'character_set-CDATA' => $language['charset'],
                                    'text_direction-CDATA' => $language['text_direction'],
                                    'date_format_short-CDATA' => $language['date_format_short'],
                                    'date_format_long-CDATA' => $language['date_format_long'],
                                    'time_format-CDATA' => $language['time_format'],
                                    'default_currency-CDATA' => $this->currencies->get_code($language['currencies_id']),
                                    'numerical_decimal_separator-CDATA' => $language['numeric_separator_decimal'],
                                    'numerical_thousands_separator-CDATA' => $language['numeric_separator_thousands']);
      
      if ( $language['parent_id'] > 0 ) 
      {
        $export_array['data']['parent_language_code'] = $this->get_code($language['parent_id']);
      }
      
      $Qdefs = $this->db
      ->select('content_group, definition_key, definition_value')
      ->from('languages_definitions')
      ->where('languages_id', $id)
      ->where_in('content_group', $groups)
      ->order_by('content_group, definition_key')
      ->get();
      
      if ($Qdefs->num_rows() > 0)
      {
        foreach($Qdefs->result_array() as $definition)
        {
          $export_array['definitions'][] = array('key' => $definition['definition_key'],
                                                 'value' => $definition['definition_value'],
                                                 'group' => $definition['content_group']);
        }
      }
      $Qdefs->free_result();
      
      $xml = $this->xml->get_xml('language', $export_array);
      $this->xml->put_header($language['code'], strlen($xml));
      
      echo $xml;
    }
  }
  
  public function get_id($code)
  {
    $Qlanguage = $this->db
    ->select('languages_id')
    ->from('languages')
    ->where('code', $code)
    ->get();
    
    $result = $Qlanguage->row_array();
    $Qlanguage->free_result();
    
    return $result['languages_id'];
  }
  
  public function get_code($id)
  {
    $Qlanguage = $this->db
    ->select('code')
    ->from('languages')
    ->where('languages_id', $id)
    ->get();
    
    $language = $Qlanguage->row_array();
    $Qlanguage->free_result();
    
    return $language['code'];
  }
  
  public function get_definitions($languages_id, $group, $search = NULL)
  {
    $this->db
    ->select('*')
    ->from('languages_definitions')
    ->where(array('languages_id' => $languages_id, 'content_group' => $group));
    
    if (!empty($search))
    {
      $this->db
      ->like('definition_key',$search)
      ->or_like('definition_value', $search);
    }
    
    $Qdefinitions = $this->db->order_by('definition_key')->get();
    
    return $Qdefinitions->result_array();
  }
  
  public function save_definition($id, $group, $key, $value)
  {
    $this->db->update('languages_definitions', array('definition_value' => $value), 
                                               array('definition_key' => $key, 
                                                     'languages_id' => $id, 
                                                     'content_group' => $group));
                                               
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function delete_definition($id)
  {
    $Qdefs = $this->db
    ->select('languages_id, content_group')
    ->from('languages_definitions')
    ->where('id', $id)
    ->get();
    
    $definition = $Qdefs->row_array();
    $Qdefs->free_result();
    
    $this->db->delete('languages_definitions', array('id' => $id));
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function add_definition($data)
  {
    $this->db->insert('languages_definitions', $data);
    
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    }
    
    return FALSE;
  }
  
  public function get_total_definitions($languages_id)
  {
    return $this->db->where('languages_id', $languages_id)->from('languages_definitions')->count_all_results();
  }
  
  public function get_total()
  {
    return $this->db->count_all('languages');
  }

}


/* End of file languages_model.php */
/* Location: ./system/modules/languages/models/languages_model.php */
