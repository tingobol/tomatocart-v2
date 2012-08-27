<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class New_Products extends TOC_Module {
  /**module code*/
  var $code = 'new_products';
  
  var $author_name = 'TomatoCart';
  
  var $author_url = 'http://www.tomatocart.com';
  
  var $version = '1.0';

  public function __construct($config)
  {
    parent::__construct();

    $this->title = lang('new_products_title');
  }

  public function index()
  {
    $data['title'] = 'New Products';
    $data['new_products_link'] = 'index.php/new_products';
    $data['product_link'] = 'index.php/products/1';
    $data['product_name'] = 'Apple iPhone 3G';
    $data['product_price'] = '$399.00';
    $data['product_image'] = 'images/products/thumbnails-iphone-03923923.jpg';
     
    return $this->load_view('index.php', $data);
  }
}