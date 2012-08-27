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
 * @filesource
 */

class Currencies extends TOC_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('currencies_model');
    $this->load->driver('cache', array('adapter' => 'file'));
  }
  
  public function show()
  {
    $this->load->view('main');
    $this->load->view('currencies_grid');
    $this->load->view('currencies_dialog');
    $this->load->view('currencies_update_rates_dialog');
  }
  
  public function list_currencies()
  {
    $this->load->library('currencies');
    
    $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
    $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    
    $currencies = $this->currencies_model->get_currencies($start, $limit);
    
    $records = array();
    if (!empty($currencies))
    {
      foreach($currencies as $currency)
      {
        $currency_name = $currency['title'];
        
        if ($currency['code'] == DEFAULT_CURRENCY)
        {
          $currency_name .= ' (' . lang('default_entry') . ')';
        }
        
        $records[] = array('currencies_id' => $currency['currencies_id'],
                           'title' => $currency_name,
                           'code' => $currency['code'],
                           'value' => $currency['value'],
                           'example' => $this->currencies->format(1499.99, $currency['code'], 1));
      }
    }
    
    return array(EXT_JSON_READER_TOTAL => $this->currencies_model->get_total(),
                 EXT_JSON_READER_ROOT => $records);
  }
  
  public function save_currency()
  {
    $error = FALSE;
    $feedback = array();
    
    $code = $this->input->post('code');
    $currencies_id = $this->input->post('currencies_id');
    
    if (!is_numeric($currencies_id) && $this->currencies_model->codeIsExist($code))
    {
      $error = TRUE;
      $feedback[] = lang('ms_error_currency_code_exist');
    }
    
    if ($error === FALSE)
    {
      $data = array('title' => $this->input->post('title'),
                    'code' => $code,
                    'symbol_left' => $this->input->post('symbol_left'),
                    'symbol_right' => $this->input->post('symbol_right'),
                    'decimal_places' => $this->input->post('decimal_places'),
                    'value' => $this->input->post('value'));
      
      $default = FALSE;
      if ( ($this->input->post('default') == 'on') || ($this->input->post('is_default') == 'on' && $code != DEFAULT_CURRENCY) )
      {
        $default = TRUE;
      }
      
      if ($this->currencies_model->save($currencies_id, $data, $default))
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
  
  public function load_currency()
  {
    $data = $this->currencies_model->get_data($this->input->post('currencies_id'));
    if ($data['code'] == DEFAULT_CURRENCY) {
      $data['is_default'] = '1';
    }
    
    return array('success' => TRUE, 'data' => $data); 
  }
  
  public function delete_currency()
  {
    $error = FALSE;
    $feedback = array();
    
    $code = $this->input->post('code');
    if ($code == DEFAULT_CURRENCY) 
    {
      $error = TRUE;
      $feedback[] = lang('introduction_delete_currency_invalid');
    }
    
    if ($error === FALSE)
    {
      if ($this->currencies_model->delete($this->input->post('cID')))
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
  
  public function delete_currencies()
  {
    $error = FALSE;
    $feedback = array();
    
    $currencies_ids = json_decode($this->input->post('batch'));
    $currencies = $this->currencies_model->get_currencies_info($currencies_ids);
    
    foreach($currencies as $currency)
    {
      if ($currency['code'] == DEFAULT_CURRENCY)
      {
        $error = TRUE;
        $feedback[] = lang('introduction_delete_currency_invalid');
        
        break;
      }
    }
    
    if ($error === FALSE)
    {
      foreach($currencies_ids as $id)
      {
        if ($this->currencies_model->delete($id) == FALSE)
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
  
  public function update_currency_rates()
  {
    if ($this->currencies_model->update_rates($this->input->post('currencies_id'), 'oanda'))
    {
      $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
    }
    
    return $response;
  }
}

/* End of file currencies.php */
/* Location: ./system/modules/currencies/controllers/currencies.php */