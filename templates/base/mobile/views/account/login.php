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

    <h1><?php echo lang('sign_in_heading'); ?></h1>
    
    <h3><?php echo lang('login_returning_customer_heading'); ?></h3>
    
    <p><?php echo lang('login_returning_customer_text'); ?></p>
    
    <?php echo toc_validation_errors('login');?>
    
    <form id="login" name="login" action="<?php echo site_url('account/login/process');?>" method="post" data-ajax="false">
        <div data-role="fieldcontain">
            <label for="email_address"><?php echo lang('field_customer_email_address');?><em>* </em></label>
            <input type="text" name="email_address" id="email_address" value="<?php echo set_value('email_address');?>" />            
        </div>      
        <div data-role="fieldcontain">            
            <label for="password"><?php echo lang('field_customer_password');?><em>* </em></label>
            <input type="password" name="password" id="password" value="<?php echo set_value('password');?>" />
        </div>
          
        <p>
	        <?php echo sprintf(lang('login_returning_customer_password_forgotten'), site_url('account/password_forgotten'));?>
        </p>
          
        <button type="submit" data-theme="a"><?php echo lang('button_sign_in'); ?></button>
    </form>
    
    <h3><?php echo lang('login_new_customer_heading'); ?></h3>
    
    <p><?php echo lang('login_new_customer_text'); ?></p>
    
    <a href="<?php echo site_url('account/create'); ?>" data-role="button" data-theme="b"><?php echo lang('button_continue'); ?></a>           
