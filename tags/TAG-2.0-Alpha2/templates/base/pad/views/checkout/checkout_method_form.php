<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package      TomatoCart
 * @author       TomatoCart Dev Team
 * @copyright    Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html
 * @link         http://tomatocart.com
 * @since        Version 2.0
 * @filesource
*/
?>

<!--  Begin: New Customer Form  -->
  <h3><?= lang('login_new_customer_heading');?></h3>
  
   <p><?= lang('login_new_customer_text');?></p>
  
  <div data-role="fieldcontain">
    <fieldset data-role="controlgroup">
      <input type="radio" name="checkout_method" id="register" value="register" checked="checked"  />
      <label for="register">Rgister Account</label>
      
      <input type="radio" name="checkout_method" id="guest" value="guest"  />
      <label for="guest">Guest Checkout</label>
    </fieldset>
  </div>
  
	<button type="submit" class="small button" id="btn-new-customer" data-theme="b"><?= lang('button_continue'); ?></button>
<!--  End: New Customer Form  -->

<!--  Begin: Login Form  -->
  <form name="login" action="<?= site_url('account/create'); ?>" method="post">
    <h3><?= lang('login_returning_customer_heading');?></h3>
  
    <p><?= lang('login_returning_customer_text');?></p>
  
  	<div data-role="fieldcontain">
  	  <label for="email_address"><?= lang('field_customer_email_address');?><em>* </em></label>
  	  <input type="text" name="email_address" id="email_address" value="" />            
  	</div>      
  	<div data-role="fieldcontain">            
  	  <label for="password"><?= lang('field_customer_password');?><em>* </em></label>
  	  <input type="password" name="password" id="password" value="" />
  	</div>
  
    <p><?= sprintf(lang('login_returning_customer_password_forgotten'), site_url('account/password_forgotten'));?></p>
      
    <button type="submit" class="small button" id="btn-login" data-theme="e"><?= lang('button_sign_in'); ?></button>
  </form>    
<!--  End: Login Form  -->