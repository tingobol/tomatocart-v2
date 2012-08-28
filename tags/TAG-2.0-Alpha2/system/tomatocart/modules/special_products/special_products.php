<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Special_Products extends TOC_Module {

  protected $code = 'special_products';

  public function __construct()
  {
    parent::__construct();

    $this->title = lang('box_specials_heading');
  }

  public function index()
  {
    $data['title'] = 'Special Products';
    $data['special_products_link'] = 'index.php/special_products';
    $data['product_link'] = 'index.php/products/1';
    $data['product_name'] = 'Apple iPhone 3G';
    $data['old_product_price'] = '$399.00';
    $data['product_price'] = '$399.00';
    $data['product_image'] = 'images/products/thumbnails-iphone-03923923.jpg';
     
     
    return $this->load_view('index.php', $data);
  }
}