<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Footer extends TOC_Module {

  protected $code = 'footer';

  public function __construct($config)
  {
    parent::__construct();

    $this->title = 'footer';
  }

  public function index()
  {
    $data['home_text'] = 'Home';
    $data['home_link'] = 'index.php';

    $data['special_text'] = 'Special';
    $data['special_link'] = 'index.php/products/specials';

    $data['new_products_text'] = 'New Products';
    $data['new_products_link'] = 'index.php/products/latest';

    $data['my_account_text'] = 'My Account';
    $data['my_account_link'] = 'index.php/account';

    $data['my_wishlist_text'] = 'My Wishlist';
    $data['my_wishlist_link'] = 'index.php/account/wishlist';

    $data['cart_content_text'] = 'Shopping Cart';
    $data['cart_content_link'] = 'index.php/checkout/shopping_cart';

    $data['checkout_text'] = 'Checkout';
    $data['checkout_link'] = 'index.php/checkout/checkout';

    $data['contact_us_text'] = 'Contact Us';
    $data['contact_us_link'] = 'index.php/info/contact_us';

    $data['guest_book_text'] = 'Guest Book';
    $data['guest_book_link'] = 'index.php/info/guestbooks';

    $data['rss_text'] = 'RSS';
    $data['rss_link'] = 'index.php/index/rss';

    return $this->load_view('index.php', $data);
  }
}