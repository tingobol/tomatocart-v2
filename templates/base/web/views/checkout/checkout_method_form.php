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

<!--  Begin: Login Form  -->
<div class="moduleBox" style="width: 49%; float: right">
    <form name="login" action="<?php echo site_url('account/create'); ?>" method="post">
        <h6><?php echo lang('login_returning_customer_heading');?></h6>

        <div class="content">
            <p><?php echo lang('login_returning_customer_text');?></p>

            <ul>
                <li>
                    <label for="email_address"><?php echo lang('field_customer_email_address');?><span class="required">*</span></label> 
                    <input type="text" id="email_address" name="email_address" value="<?php echo set_value('email_address');?>" />
                </li>
                <li>
                    <label for="password"><?php echo lang('field_customer_password');?><em>*</em></label> 
                    <input type="password" id="password" name="password" value="<?php echo set_value('password');?>" />
                </li>
            </ul>

            <p><?php echo sprintf(lang('login_returning_customer_password_forgotten'), site_url('account/password_forgotten'));?></p>

            <p align="right">
                <button type="submit" class="small button" id="btn-login"><?php echo lang('button_sign_in'); ?></button>
            </p>
        </div>
    </form>
</div>
<!--  End: Login Form  -->

<!--  Begin: New Customer Form  -->
<div class="moduleBox" style="width: 49%">
    <h6><?php echo lang('login_new_customer_heading');?></h6>

    <div class="content">
        <ul>
            <li>
            	<input type="radio" id="checkout_method_register" name="checkout_method" value="register" checked="checked" /> 
            	<label for="checkout_method_register">Rgister Account</label>
            </li>
            <li>
            	<input type="radio" id="checkout_method_guest" name="checkout_method" value="guest" /> 
            	<label for="checkout_method_guest">Guest Checkout</label>
			</li>
        </ul>

        <p><?php echo lang('login_new_customer_text');?></p>

        <p align="right">
            <button type="submit" class="small button" id="btn-new-customer"><?php echo lang('button_continue'); ?></button>
        </p>
    </div>
</div>
<!--  End: New Customer Form  -->

<div style="clear: both; margin: 0"></div>
