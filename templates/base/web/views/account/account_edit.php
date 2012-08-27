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

<h1><?php echo lang('account_edit_heading'); ?></h1>

<?php echo toc_validation_errors('account_edit'); ?>

<form name="account_edit" action="<?php echo site_url('account/edit/save'); ?>" method="post">
    <div class="moduleBox">
        <h6 class="title"><em><?php echo lang('form_required_information'); ?></em><?php echo lang('my_account_title'); ?></h6>
    
        <div class="content">
            <ul>
                <?php
                    if (config('ACCOUNT_GENDER') > -1) :
                ?>
                    <li>
                        <label class="field_label" for="gender1"><?php echo lang('field_customer_gender') . ((config('ACCOUNT_GENDER') > 0) ? '<em>*</em>' : ''); ?></label>
                        <input type="radio" value="m" id="gender1" name="gender" <?php echo set_radio('gender', 'm', isset($customers_gender) && $customers_gender == 'm'); ?> /><label for="gender1"><?php echo lang('gender_male'); ?></label>
                        <input type="radio" value="f" id="gender2" name="gender" <?php echo set_radio('gender', 'f', isset($customers_gender) && $customers_gender == 'f'); ?> /><label for="gender2"><?php echo lang('gender_female'); ?></label>
                    </li>
                    
                <?php
                    endif;
                ?>
                <li>
                    <label class="field_label" for="firstname"><?php echo lang('field_customer_first_name'); ?><em>*</em></label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo isset($customers_firstname) ? $customers_firstname : set_value('firstname'); ?>" />
                </li>
                <li>
                    <label class="field_label" for="lastname"><?php echo lang('field_customer_last_name'); ?><em>*</em></label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo isset($customers_lastname) ? $customers_lastname : set_value('lastname'); ?>" />
                </li>
                 <?php 
                    if (config('ACCOUNT_DATE_OF_BIRTH') == '1') :
                ?>
                <li>
                    <label class="field_label" for="dob_days"><?php echo lang('field_customer_date_of_birth'); ?><em>*</em></label>
                    <input type="text" id="dob_days" name="dob_days" value="<?php echo isset($customers_dob) ? mdate('%Y-%m-%d ', human_to_unix($customers_dob)) : set_value('dob_days'); ?>" />
                </li>
                <?php 
                    endif;
                ?>
                <li>
                    <label class="field_label" for="email_address"><?php echo lang('field_customer_email_address'); ?><em>*</em></label>
                    <input type="text" id="email_address" name="email_address" value="<?php echo isset($customers_email_address) ? $customers_email_address : set_value('email_address'); ?>" />
                </li>
                <?php 
                    if (config('ACCOUNT_NEWSLETTER') == '1') :
                ?>
                <li>
                    <label class="field_label" for="newsletter"><?php echo lang('field_customer_newsletter'); ?></label>
                    <input type="checkbox" id="newsletter" name="newsletter" value="1" <?php echo set_checkbox('newsletter', '1', isset($customers_newsletter) && $customers_newsletter == '1'); ?> />
                </li>
                <?php 
                    endif;
                ?>
            </ul>
        </div>
    </div>

    <div class="submitFormButtons clearfix">
      <button class="button fr"><?php echo lang('button_continue'); ?></button>
      <a href="<?php echo site_url('account'); ?>" class="button"><?php echo lang('button_back'); ?></a>
    </div>
</form>

<script>
  $(function() {$( "#dob_days" ).datepicker({showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true, dateFormat: 'yy-mm-dd'});});
</script>