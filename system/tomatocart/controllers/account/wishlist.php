<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Wishlist Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Wishlist extends TOC_Controller {
	/**
	 * Constructor
	 *
	 * @access public
	 * @param string
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Default Function
	 *
	 * @access public
	 * @param string
	 * @return void
	 */
	public function index(){
	    $data['wishlist_products'] = $this->wishlist->get_products();
	    $this->template->build('account/wishlist', $data);
	}
	
	public function indexb()
	{
		$data['title'] = 'My Wishlist';
		$data['text_products'] = 'Products';
		$data['text_comments'] = 'Comments';
		$data['text_date'] = 'Date Added';
		$data['text_delete'] = 'Delete';
		$data['text_cart'] = 'Add To Cart';
		$data['text_update'] = 'Update';
		$data['text_back'] = 'Back';
		$data['text_empty'] = 'There is no product in your wishlist.';
		$data['text_share'] = 'Share Your Wishlist';
		$data['text_required'] = '* Required informationShare Your Wishlist';
		$data['text_name'] = 'Your Name:';
		$data['text_from_email'] = 'Your E-Mail Address:';
		$data['text_emails'] = 'Emails: (seperated by comma)';
		$data['text_message'] = 'Message:';
		$data['text_continue'] = 'Continue';

		$data['wishlist_products'] = array (
		0 => array ('id' => '5',
                  'name' => '17" MACBOOK PRO MB166LL/A',
                  'image' => 'images/products/thumbnails-l305d-s5904-39293323.jpg',
                  'price' => 1199, 'date_added' => '07/16/2011',
                  'link' => '',
                  'comments' => ''), 
		1 => array ('id' => '3',
                  'name' => 'ThinkCentre M57p',
                  'image' => 'images/products/thumbnails-l305d-s5904-39293323.jpg',
                  'price' => 800, 'date_added' => '07/16/2011',
                  'link' => '',
                  'comments' => '', )
		);

		$data['link_wishlist'] = '';
		$data['link_cart'] = '';
		$data['link_delete'] = '';
		$data['link_update'] = '';
		$data['link_back'] = '';
		$data['link_share'] = '';
		$data['link_continue'] = '';

		$data['value_name'] = 'kemin bao';
		$data['value_from_email'] = 'bkm1010@163.com';

		$this->template->build('account/wishlist', $data);
	}
}

/* End of file wishlist.php */
/* Location: ./system/tomatocart/controllers/account/wishlist.php */