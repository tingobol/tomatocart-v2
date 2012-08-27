<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Popular_Search_Terms extends TOC_Module {
  /**module code*/
  var $code = 'popular_search_terms';
  
  var $author_name = 'TomatoCart';
  
  var $author_url = 'http://www.tomatocart.com';
  
  var $version = '1.0';

  var $params = array(
        array('name' => 'MODULE_POPULAR_SEARCH_TERM_CACHE', 
              'title' => 'Cache Contents', 
              'type' => 'numberfield',
         		  'value' => '60',
              'description' => 'Number of minutes to keep the contents cached (0 = no cache)'));
        
  public function __construct()
  {
    parent::__construct();
    
    $this->title = lang('box_popular_search_terms_tag_cloud_heading');
  }

  public function index()
  {
    $data['title'] = 'Popular Search Terms';
     
    $data['keywords'] = array(
    array('link_href' => 'index.php/keywords/apple', 'font_size' => '9px', 'title' => 'apple'),
    array('link_href' => 'index.php/keywords/sony', 'font_size' => '9px', 'title' => 'sony'),
    array('link_href' => 'index.php/keywords/acer', 'font_size' => '25px', 'title' => 'acer'),
    array('link_href' => 'index.php/keywords/benq', 'font_size' => '13px', 'title' => 'benq'),
    array('link_href' => 'index.php/keywords/lenovo', 'font_size' => '15px', 'title' => 'lenovo'),

    array('link_href' => 'index.php/keywords/nokia', 'font_size' => '20px', 'title' => 'nokia'),
    array('link_href' => 'index.php/keywords/LG', 'font_size' => '20px', 'title' => 'LG'),
    array('link_href' => 'index.php/keywords/Samsung', 'font_size' => '12px', 'title' => 'Samsung'));
     
    return $this->load_view('index.php', $data);
  }
}