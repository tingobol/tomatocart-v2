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

<h1><?php echo lang('password_forgotten_heading'); ?></h1>

<form name="password_forgotten" action="<?php echo site_url('password_forgotten/process');?>" method="post">

    <div class="box">
        <h6 class="title"><?php echo lang('password_forgotten_heading'); ?></h6>
        
        <div class="content">
            <p><?php echo lang('password_forgotten'); ?></p>
            
            <ol>
                <li>
                    <label for="email_address"><?php echo lang('field_customer_email_address'); ?></label>
                    <input type="text" id="email_address" name="email_address">
                </li>
            </ol>
        </div>
    </div>
    
    <div class="submitFormButtons">
        <a class="button" href="<?php echo site_url('account/login'); ?>"><?php echo lang('button_back'); ?></a>
        <button type="submit" class="button continue"><?php echo lang('button_continue'); ?></button>
    </div>

</form>
