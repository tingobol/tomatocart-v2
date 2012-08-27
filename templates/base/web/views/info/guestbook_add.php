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

<h1><?php echo lang('guestbook_heading'); ?></h1>

<?php
    echo toc_validation_errors('guestbook');
?>

<div class="moduleBox">
    <h6 class="title"><?php echo lang('guestbook_new_heading'); ?></h6>
    
    <div class="content">
		    <form id="guestbooks_edit" name="guestbooks_edit" action="<?php echo site_url('info/guestbooks/save');?>" method="post">
            <ul>  
                <li>
                    <label class="field_label" for="title"><?php echo lang('field_title');?><em>*</em></label>
                    <input type="text" id="title" name="title" value="<?php echo set_value('title');?>" />
                </li>
                <li>
                    <label class="field_label" for="email"><?php echo lang('field_email');?><em>*</em></label>
                    <input type="text" id="email" name="email" value="<?php echo set_value('email');?>" />
                </li>  
                <li>
                    <label class="field_label" for="url"><?php echo lang('field_url');?></label>
                    <input type="text" id="url" name="url" value="<?php echo set_value('url');?>" />
                </li>  
                <li>
                    <label class="field_label" for="content"><?php echo lang('field_content');?><em>*</em></label>
                    <textarea rows="5" cols="29" id="content" name="content"><?php echo set_value('content'); ?></textarea>
                </li>
            </ul>
            
            <div class="submitFormButtons clearfix">
                <button class="button fr"><?php echo lang('button_continue'); ?></button>
                <a href="<?php echo site_url('info/guestbooks'); ?>" class="button"><?php echo lang('button_back'); ?></a>
            </div>
        </form>
    </div>
</div>