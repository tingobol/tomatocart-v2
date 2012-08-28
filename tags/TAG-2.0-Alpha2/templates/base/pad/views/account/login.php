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

<div data-role="content">
	<h1><?php echo lang('sign_in_heading'); ?></h1>
    
    <div class="ui-grid-a">
    	<div class="ui-block-a">
    		<h3><?= lang('login_returning_customer_heading'); ?></h3>
    		
    		<p><?= lang('login_returning_customer_text'); ?></p>
    		
    		<?php echo validation_errors();?>
    		
            <form id="login" name="login" action="<?= site_url('account/login/process');?>" method="post" data-ajax="false">
                <div data-role="fieldcontain">
                    <label for="usermobile"><?= lang('field_customer_email_address');?><em>* </em></label>
                    <input type="text" name="email_address" id="email_address" value="" />            
                </div>      
                <div data-role="fieldcontain">            
                    <label for="shippingmobile"><?= lang('field_customer_password');?><em>* </em></label>
                    <input type="password" name="password" id="password" value="" />
                </div>
                  
                <p><?= sprintf(lang('login_returning_customer_password_forgotten'), site_url('account/password_forgotten'));?></p>
                  
                <button type="submit" data-theme="a"><?= lang('button_sign_in'); ?></button>
            </form>
    	</div>
    	<div class="ui-block-b">
            <h3><?= lang('login_new_customer_heading'); ?></h3>
            
            <p><?= lang('login_new_customer_text'); ?></p>
            
            <a href="<?= site_url('account/create'); ?>" data-role="button" data-theme="b"><?= lang('button_continue'); ?></a>   
    	</div>
    </div>
</div>