<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feature_Products extends TOC_Module {
  /**module code*/
  var $code = 'feature_products';
  
  var $author_name = 'TomatoCart';
  
  var $author_url = 'http://www.tomatocart.com';
  
  var $version = '1.0';
  
  var $params = array(
        array('name' => 'MODULE_FEATURE_PRODUCTS_MAX_DISPLAY', 
              'title' => 'Maximum Entries To Display', 
              'type' => 'numberfield',
         		  'value' => '9',
              'description' => 'Maximum number of feature products to display'));

  public function __construct($config)
  {
    parent::__construct();

    $this->config = $config;

    $this->title = lang('feature_products_title');
  }

  public function index()
  {
    $this->ci->load->model('products_model');

    $data['title'] = 'Feature Products';

    $products = $this->ci->products_model->get_feature_products();
    foreach($products as $product) {
      $data['products'][] = array(
        'product_link' => site_url('products/info/index/' . $product['products_id']),
        'products_name' => $product['products_name'],
      	'products_image' => 'images/products/thumbnails/' . $product['image'],
      	'products_price' => $this->ci->currencies->format($product['products_price'])
      );
    }
     
    return $this->load_view('index.php', $data);
  }
}