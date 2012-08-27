<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currencies extends TOC_Module {
  /**module code*/
  var $code = 'currencies';
  
  var $author_name = 'TomatoCart';
  
  var $author_url = 'http://www.tomatocart.com';
  
  var $version = '1.0';

  public function __construct()
  {
    parent::__construct();

    $this->title = lang('box_currencies_heading');
  }

  public function index()
  {
    $data['title'] = lang('box_currencies_heading');
     
    $currencies = $this->ci->currencies->get_data();
    foreach($currencies as $code => $currency)
    {
      $data['currencies'][] = array('code' => $code, 'title' => $currency['title']);
    }
    $data['currency_code'] = $this->ci->currencies->get_code();
     
    return $this->load_view('index.php', $data);
  }
}