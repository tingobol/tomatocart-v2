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
    <h1><?php echo lang('create_account_heading'); ?></h1>
    
    <?php echo toc_validation_errors('create'); ?>
    
    <form id="create" name="create" action="<?php echo site_url('account/create/save'); ?>" method="post">
    <?php 
        if (config('ACCOUNT_GENDER') > -1) :
    ?>
        <div data-role="fieldcontain">
            <fieldset data-role="controlgroup" data-type="horizontal" data-role="fieldcontain">
                <legend><?php echo lang('field_customer_gender'); ?><?php echo ((config('ACCOUNT_GENDER') > -1) ? '<span class="required">*</span>' : ''); ?></legend>
                <input type="radio" name="gender" id="gender1" value="m" <?php echo set_radio('gender', 'm', TRUE); ?> />
                <label for="gender1"><?php echo lang('gender_male'); ?></label>
                
                <input type="radio" name="gender" id="gender2" value="f" <?php echo set_radio('gender', 'f'); ?> />
                <label for="gender2"><?php echo lang('gender_female'); ?></label>
            </fieldset>        
        </div>   
    <?php 
        endif; 
    ?> 
        
        <div data-role="fieldcontain">
            <?php echo draw_label(lang('field_customer_first_name'), null, 'firstname', TRUE); ?>
            <input type="text" name="firstname" id="firstname" value="<?php echo set_value('firstname'); ?>" />
        </div>
        
        <div data-role="fieldcontain">            
          	<?php echo draw_label(lang('field_customer_last_name'), null, 'lastname', TRUE); ?>
        	<input type="text" name="lastname" id="lastname" value="<?php echo set_value('lastname'); ?>" />
        </div>
    
    <?php 
        if (config('ACCOUNT_DATE_OF_BIRTH') == '1') :
    ?>
        <div data-role="fieldcontain">
            <?php echo draw_label(lang('field_customer_date_of_birth'), null, 'dob_days', (config('ACCOUNT_COMPANY') > 0)); ?>
          	<input type="date" id="dob_days" name="dob_days" value="<?php echo set_value('dob_days'); ?>" />
        </div>
    <?php 
        endif; 
    ?>
        
        <div data-role="fieldcontain">
            <?php echo draw_label(lang('field_customer_email_address'), null, 'email_address', TRUE); ?>
        	<input type="text" name="email_address" id="email_address" value="<?php echo set_value('email_address'); ?>" />
        </div>
        
    <?php 
        if (config('ACCOUNT_NEWSLETTER') == '1') :
    ?>
        <div data-role="fieldcontain">
            <fieldset data-role="controlgroup">
             	<legend><?php echo lang('field_customer_newsletter'); ?></legend>
        			<input type="checkbox" name="newsletter" id="newsletter" class="custom" value="1" />
        			<label for="newsletter">&nbsp;</label>
            </fieldset>
        </div>
    <?php 
        endif; 
    ?>
        
        <div data-role="fieldcontain">            
            <?php echo draw_label(lang('field_customer_password'), null, 'password', TRUE); ?>
            <input type="password" id="password" name="password" />
        </div>
        
        <div data-role="fieldcontain">            
            <?php echo draw_label(lang('field_customer_password_confirmation'), null, 'confirmation', TRUE); ?>
            <input type="password" id="confirmation" name="confirmation" />
        </div>
        
    <?php 
        if (config('DISPLAY_PRIVACY_CONDITIONS') == '1') :
    ?>
        <div data-role="fieldcontain">
        	<h3><?php echo lang('create_account_terms_heading'); ?></h3>
        	
        	<p><?php echo lang('create_account_terms_description'); ?></p>
        	
            <fieldset data-role="controlgroup">
             	<legend>&nbsp;</legend>
        		<input type="checkbox" name="privacy_conditions" id="privacy_conditions" class="custom" value="1" <?php echo set_checkbox('privacy_conditions', '1'); ?> />
        		<label for="privacy_conditions"><?php echo lang('create_account_terms_confirm'); ?></label>
            </fieldset>
        </div>
    <?php 
        endif; 
    ?>
    
    	<button type="submit" data-theme="a"><?php echo lang('button_continue'); ?></button>
    
    	<a href="<?php echo site_url('account'); ?>" data-role="button" data-theme="b"><?php echo lang('button_back'); ?></a>
	</form>
</div>

<link rel="stylesheet" href="<?php echo base_url();?>templates/default/mobile/css/jquery.ui.datepicker.mobile.css" /> 
<script src="<?php echo base_url();?>templates/base/mobile/javascript/jQuery.ui.datepicker.js"></script>
<script src="<?php echo base_url();?>templates/base/mobile/javascript/jquery.ui.datepicker.mobile.js"></script>
<script>
	//reset type=date inputs to text
	$( document ).bind( "mobileinit", function(){
		$.mobile.page.prototype.options.degradeInputs.date = true;
	});	
</script>