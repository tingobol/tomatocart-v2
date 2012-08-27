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

<h1><?php echo lang('account_password_heading'); ?></h1>

<?php echo toc_validation_errors('account_password'); ?>

<form name="account_password" action="<?php echo site_url('account/password/save'); ?>" method="post">
    <div class="moduleBox">
        <h6><em><?php echo lang('form_required_information'); ?></em><?php echo lang('my_password_title'); ?></h6>
        
        <div class="content">
            <ul>
                <li>
                    <label class="field_label" for="password_current"><?php echo lang('field_customer_password_current'); ?><em>*</em></label>
                    <input type="password" id="password_current" name="password_current" value="<?php echo set_value('password_current'); ?>" />
                </li>
                <li>
                    <label class="field_label" for="password_new"><?php echo lang('field_customer_password_new'); ?><em>*</em></label>
                    <input type="password" id="password_new" name="password_new" value="<?php echo set_value('password_new'); ?>" />
                </li>
                <li>
                    <label class="field_label" for="password_confirmation"><?php echo lang('field_customer_password_confirmation'); ?><em>*</em></label>
                    <input type="password" id="password_confirmation" name="password_confirmation"  value="<?php echo set_value('password_confirmation'); ?>" />
                </li>
            </ul>
        </div>
    </div>
    
    <div class="submitFormButtons clearfix">
       <button class="button fr"><?php echo lang('button_continue'); ?></button>
       <a href="<?php echo site_url('account'); ?>" class="button"><?php echo lang('button_back'); ?></a>
    </div>
</form>
