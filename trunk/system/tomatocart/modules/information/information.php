<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Information extends TOC_Module {
  /**module code*/
  var $code = 'information';
  
  var $author_name = 'TomatoCart';
  
  var $author_url = 'http://www.tomatocart.com';
  
  var $version = '1.0';
  
  public function __construct($config)
  {
    parent::__construct();

    $this->title = lang('box_information_heading');
  }

  public function index()
  {
    $data['title'] = 'Information';
     
    $data['informations'] = array(
    array('link_title' => 'About Us', 'link_href' => 'index.php/info/1'),
    array('link_title' => 'Shipping &amp;Returns', 'link_href' => 'index.php/info/2'),
    array('link_title' => 'Privacy Notice', 'link_href' => 'index.php/info/3'),
    array('link_title' => 'Conditions of Use', 'link_href' => 'index.php/info/4'),
    array('link_title' => 'Imprint', 'link_href' => 'index.php/info/5'));
     
    $data['contact_link_title'] = 'Contact Us';
    $data['contact_link'] = 'index.php/info/contact_us';
    $data['sitemap_link_title'] = 'Sitemap';
    $data['sitemap_link'] = 'index.php/info/sitemap';
     
    return $this->load_view('index.php', $data);
  }
}