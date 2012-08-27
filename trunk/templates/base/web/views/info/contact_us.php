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

<h1><?php echo lang('info_contact_heading'); ?></h1>

<?php
   echo toc_validation_errors('contact');
?>

<div class="moduleBox">
    <h6><?php echo lang('contact_title'); ?></h6>
    
    <div class="content boxContactUs clearfix">
        <div class="storeName"><?php echo nl2br(config('STORE_NAME_ADDRESS')); ?></div>
        <div class="storeAddress"><strong><?php echo lang('contact_store_address_title'); ?></strong><br /><img src="<?php echo image_url('arrow_south_east.gif') ?>" /></div>
        <p><?php echo lang('contact'); ?></p>
    </div>
</div>

<form name="contact" action="<?php echo site_url('contact_save');?>" method="post">
    <div class="moduleBox clearfix">
        <div class="content">
            <ul>
                <?php 
                    if (!empty($departments)) : 
                ?>
                <li>
                    <label class="field_label" for="department_email"><?php echo lang('contact_departments_title');?><em>*</em></label>
                    <select name="department_email" id="department_email">
                    <?php
                        foreach($departments as $email => $title) :
                    ?>
                          <option value="<?php echo $email; ?>" <?php echo set_select('department_email', $email); ?>><?php echo $title; ?></option>
                    <?php
                        endforeach;
                    ?>
                    </select>
                </li>
                <?php
                    endif;
                ?>
                <li>
                    <label class="field_label" for="name"><?php echo lang('contact_name_title');?><em>*</em></label>
                    <input type="text" name="name" id="name" value="<?php echo set_value('name'); ?>" />
                </li>
                <li>
                    <label class="field_label" for="telephone"><?php echo lang('contact_telephone_title'); ?></label>
                    <input type="text" name="telephone" id="telephone" value="<?php echo set_value('telephone'); ?>" />
                </li>
                <li>
                    <label class="field_label" for="email"><?php echo lang('contact_email_address_title'); ?><em>*</em></label>
                    <input type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" />
                </li>
                <li>
                    <label class="field_label" for="enquiry"><?php echo lang('contact_enquiry_title'); ?><em>*</em></label>
                    <textarea name="enquiry" id="enquiry" cols="39" rows="5"><?php echo set_value('enquiry'); ?></textarea>
                </li>
            </ul>
        </div>
        
        <div class="submitFormButtons clearfix">
            <button class="button fr"><?php echo lang('button_continue'); ?></button>
        </div>
    </div>
</form>