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

<h1><?php echo lang('create_account_heading'); ?></h1>

<?php echo toc_validation_errors('create'); ?>

<form id="create" name="create" action="<?php echo site_url('account/create/save'); ?>" method="post">
    <div class="moduleBox">
        <h6><em><?php echo lang('form_required_information'); ?></em><?php echo lang('my_account_title'); ?></h6>
        
        <div class="content">
            <ul>
                <?php 
                    if (config('ACCOUNT_GENDER') > -1) :
                ?>
                <li>
                    <label class="field_label" for="gender1"><?php echo lang('field_customer_gender') . ((config('ACCOUNT_GENDER') > 0) ? '<em>*</em>' : ''); ?></label>
                    <input type="radio" value="m" id="gender1" name="gender" <?php echo set_radio('gender', 'm', TRUE); ?> /><label for="gender1"><?php echo lang('gender_male'); ?></label>
                    <input type="radio" value="f" id="gender2" name="gender" <?php echo set_radio('gender', 'f'); ?> /><label for="gender2"><?php echo lang('gender_female'); ?></label>
                </li>
                <?php 
                    endif;
                ?>
                <li>
                    <label class="field_label" for="firstname"><?php echo lang('field_customer_first_name'); ?><em>*</em></label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo set_value('firstname'); ?>" />
                </li>
                <li>
                    <label class="field_label" for="lastname"><?php echo lang('field_customer_last_name'); ?><em>*</em></label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo set_value('lastname'); ?>" />
                </li>
                <?php 
                    if (config('ACCOUNT_DATE_OF_BIRTH') == '1') :
                ?>
                <li>
                    <label class="field_label" for="dob_days"><?php echo lang('field_customer_date_of_birth'); ?><em>*</em></label>
                    <input type="text" id="dob_days" name="dob_days" value="<?php echo set_value('dob_days'); ?>" /><i>01/01/1991</i>
                </li>
                <?php 
                    endif;
                ?>
                <li>
                    <label class="field_label" for="email_address"><?php echo lang('field_customer_email_address'); ?><em>*</em></label>
                    <input type="text" id="email_address" name="email_address" value="<?php echo set_value('email_address'); ?>" />
                </li>
                <?php 
                    if (config('ACCOUNT_NEWSLETTER') == '1') :
                ?>
                <li>
                    <label class="field_label" for="newsletter"><?php echo lang('field_customer_newsletter'); ?></label>
                    <input type="checkbox" value="1" id="newsletter" name="newsletter" <?php echo set_checkbox('newsletter', '1'); ?> />
                </li>
                <?php 
                    endif;
                ?>
                <li>
                    <label class="field_label" for="password"><?php echo lang('field_customer_password'); ?><em>*</em></label>
                    <input type="password" id="password" name="password" />
                </li>
                <li>
                    <label class="field_label" for="confirmation"><?php echo lang('field_customer_password_confirmation'); ?><em>*</em></label>
                    <input type="password" id="confirmation" name="confirmation" />
                </li>
            </ul>
        </div>
    </div>
        
    <?php 
        if (config('DISPLAY_PRIVACY_CONDITIONS') == '1') :
    ?>
    <div class="box">
        <h6><?php echo lang('create_account_terms_heading'); ?></h6>
        
        <div class="contents">
            <?php echo lang('create_account_terms_description'); ?>
            
            <ul>
                <li>
                    <input type="checkbox" value="1" id="privacy_conditions" name="privacy_conditions" <?php echo set_checkbox('privacy_conditions', '1'); ?> />
                    <label for="privacy_conditions"><?php echo lang('create_account_terms_confirm'); ?></label>
                </li>
            </ul>
        </div>
    </div>
    <?php 
        endif;
    ?>
    
    <div class="submitFormButtons clearfix">
       <button class="button fr"><?php echo lang('button_continue'); ?></button>
       <a href="<?php echo site_url('account'); ?>" class="button"><?php echo lang('button_back'); ?></a>
    </div>
</form>


<script>
  $(function() {$( "#dob_days" ).datepicker({showOn: "button", buttonImage: "images/calendar.gif", buttonImageOnly: true, dateFormat: 'yy-mm-dd'});});
</script>