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
 * Save Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-account-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */

class Save extends TOC_Controller {
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
	public function index()
	{
		$data['title'] = 'My Account Information';
		$data['text_title'] = 'My Account';
		$data['text_gender'] = 'Gender:';
		$data['text_male'] = 'Male';
		$data['text_female'] = 'Female';
		$data['text_firstname'] = 'First Name:';
		$data['text_lastname'] = 'Last Name:';
		$data['text_email'] = 'E-Mail Address:';
		$data['text_group'] = 'Customer Group:';
		$data['text_credit'] = 'Store Credit:';
		$data['text_continue'] = 'Continue';
		$data['text_required'] = '* Required information';


		$data['value_firstname'] = 'kemin';
		$data['value_lastname'] = 'bao';
		$data['value_email'] = 'bkm1010@163.com';
		$data['value_group'] = '10';
		$data['value_credit'] = '$0.00';

		$data['account_gender'] = 1;
		$data['account_date_of_birth'] = 1;

		$data['link_save'] = '';
		$data['link_continue'] = '';

		if (true) {
			$data['text_title'] = 'Your Account Has Been Created!';
			$data['text_success'] = 'Congratulations! Your new account has been successfully created! You can now take advantage of member privileges to enhance your online shopping experience with us. If you have any questions about the operation of this online shop, please email the <a href="contact.html">store owner</a>.<br><br>A confirmation has been sent to the provided email address. If you have not received it within the hour, please contact us.';
			$data['text_continue'] = 'Continue';

			$data['link_img'] = 'images/account_successs.png';
			$data['link_continue'] = '';
			$this->template->build('account/create', $data);
		} else {

		}

	}
}

/* End of file save.php */
/* Location: ./system/tomatocart/controllers/account/save.php */