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

<div class="moduleBox">
    <div class="content">
        <ul>
        	<?php 
        	    if (!$is_logged_on): 
        	?>
        	<div data-role="fieldcontain">
                <?php echo draw_label(lang('field_customer_email_address'), null, 'billing_email_address', TRUE); ?>
                <input type="text" name="billing_email_address" id="billing_email_address" value="<?php echo $billing_email_address; ?>" />
        	</div>
            <?php 
                endif; 
            ?>
        		
            <?php 
                if ((!$is_logged_on) && ($checkout_method == 'register')) : 
            ?>
        	<div data-role="fieldcontain">       
                <?php echo draw_label(lang('field_customer_password'), null, 'billing_password', TRUE); ?>
                <input type="password" id="billing_password" name="billing_password" />
        	</div>
        	
        	<div data-role="fieldcontain">       
                <?php echo draw_label(lang('field_customer_password_confirmation'), null, 'confirmation', TRUE); ?>
                <input type="password" id="confirmation" name="confirmation" />
        	</div>
	        <?php 
	            endif; 
	        ?>
        
        <?php
            if ($is_logged_on && count($address_books) > 0) :
        ?>
        <li>
            <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
                <?php echo '<b>' . lang('please_select') . '</b><br />'; ?>
            </div>
            
            <p style="margin-top: 0px;"><?php echo lang('choose_billing_address'); ?></p>
        </li>    
        <li style="margin-bottom: 10px">
        <?php
            $address = array();
            foreach ($address_books as $address_book) {
                $address[$address_book['address_book_id']] = address_format($address_book, ', ');
            }
            
            echo form_dropdown('sel_billing_address', $address);
        ?>
        </li>
        <?php
            endif;
        ?>
        
        <div id="billingAddressDetails" style="display: <?php echo ($create_billing_address == FALSE) ? 'none' : ''; ?>">
        
        <?php 
            if (config('ACCOUNT_GENDER') > -1) :
        ?>
        	<div data-role="fieldcontain">
                <fieldset data-role="controlgroup" data-type="horizontal" data-role="fieldcontain">
                    <legend><?php echo lang('field_customer_gender'); ?><span class="required">*</span></legend>
                    <input type="radio" name="billing_gender" id="gender1" value="m" <?php echo (($billing_gender == 'm') ? 'checked="checked"' : ''); ?> />
                    <label for="gender1"><?php echo lang('gender_male'); ?></label>
                    
                    <input type="radio" name="billing_gender" id="gender2" value="f" <?php echo (($billing_gender == 'f') ? 'checked="checked"' : ''); ?> />
                    <label for="gender2"><?php echo lang('gender_female'); ?></label>
                </fieldset>        
        	</div>   
        <?php 
            endif;
        ?>
          
        	<div data-role="fieldcontain">            
                <?php echo draw_label(lang('field_customer_first_name'), null, 'billing_firstname', TRUE); ?>
        	  	<input type="text" name="billing_firstname" id="billing_firstname" value="<?php echo $billing_firstname; ?>" />
        	</div>
        	
        	<div data-role="fieldcontain">            
            	<?php echo draw_label(lang('field_customer_last_name'), null, 'billing_lastname', TRUE); ?>
        	  	<input type="text" name="billing_lastname" id="billing_lastname" value="<?php echo $billing_lastname; ?>" />
        	</div>
        
        <?php 
         if (config('ACCOUNT_COMPANY') > -1) :
        ?>
        	<div data-role="fieldcontain">
            	<?php echo draw_label(lang('field_customer_company'), null, 'billing_company', (config('ACCOUNT_COMPANY') > 0)); ?>
        	  	<input type="text" name="billing_company" id="billing_company" value="<?php echo $billing_company; ?>" />
        	</div>
        <?php 
         endif;
        ?>
        	<div data-role="fieldcontain">  
            	<?php echo draw_label(lang('field_customer_street_address'), null, 'billing_street_address', TRUE); ?>
        	  	<input type="text" name="billing_street_address" id="billing_street_address" value="<?php echo $billing_street_address; ?>" />
        	</div>
        <?php
          if (config('ACCOUNT_SUBURB') > -1) :
        ?>
        	<div data-role="fieldcontain">            
                <?php echo draw_label(lang('field_customer_suburb'), null, 'billing_suburb', (config('ACCOUNT_SUBURB') > 0)); ?>
        	  	<input type="text" name="billing_suburb" id="billing_suburb" value="<?php echo $billing_suburb; ?>" />
        	</div>
        <?php
          endif;
        ?>
        
        <?php
          if (config('ACCOUNT_POST_CODE') > -1) :
        ?>
        	<div data-role="fieldcontain">       
                <?php echo draw_label(lang('field_customer_post_code'), null, 'billing_postcode', (config('ACCOUNT_POST_CODE') > 0)); ?>
        	  	<input type="text" name="billing_postcode" id="billing_postcode" value="<?php echo $billing_postcode; ?>" />
        	</div>
        <?php
          endif;
        ?>
        	<div data-role="fieldcontain">            
            	<?php echo draw_label(lang('field_customer_city'), null, 'billing_city', TRUE); ?>
        	  	<input type="text" name="billing_city" id="billing_city" value="<?php echo $billing_city; ?>" />
        	</div>
          
          	<div data-role="fieldcontain">     
            	<?php echo draw_label(lang('field_customer_country'), null, 'billing_country', TRUE); ?>
          	    <?php echo form_dropdown('billing_country', $countries, $billing_country_id, 'id="billing_country"'); ?>
          	</div>
        
        <?php 
            if (config('ACCOUNT_STATE') > -1) :
        ?>
            <div data-role="fieldcontain" id="li-billing-state">
            	<?php echo draw_label(lang('field_customer_state'), null, 'billing_state', (config('ACCOUNT_STATE') > 0)); ?>
                <?php 
                    if (count($states) > 0) :
                ?>
	                <?php echo form_dropdown('billing_state', $states, $billing_state, 'id="billing_state"'); ?>
                <?php 
                    else:
                ?>
                  	<input type="text" id="billing_state" name="billing_state" value="<?php echo $billing_state; ?>" />
                <?php   
                    endif;
                ?>
            </div>  
        <?php 
            endif;
        ?>
        
        <?php 
            if (config('ACCOUNT_TELEPHONE') > -1) :
        ?>
          	<div data-role="fieldcontain">     
                <?php echo draw_label(lang('field_customer_telephone_number'), null, 'billing_telephone', (config('ACCOUNT_TELEPHONE') > 0)); ?>
            	<input type="text" name="billing_telephone" id="billing_telephone" value="<?php echo $billing_telephone; ?>" />
          	</div>
        <?php 
            endif;
        ?>
        
        <?php 
            if (config('ACCOUNT_FAX') > -1) :
        ?>
            <div data-role="fieldcontain">     
                <?php echo draw_label(lang('field_customer_fax_number'), null, 'billing_fax', (config('ACCOUNT_FAX') > 0)); ?>
                <input type="text" name="billing_fax" id="billing_fax" value="<?php echo $billing_fax; ?>" />
            </div>
        <?php 
            endif;
        ?>
        </div>
        
        <?php 
            if (!$is_logged_on) :
        ?>
        <div data-role="fieldcontain">
            <fieldset data-role="controlgroup">
                <?php echo form_checkbox('create_billing_address', 'on', $create_billing_address, 'id="create_billing_address"'); ?>
            	<label for="create_billing_address"><?php echo lang('create_new_billing_address'); ?></label>
            </fieldset>
        </div>
        <?php 
            endif;
        ?>
        
        <?php 
            if ($is_virtual_cart === FALSE) :
        ?>
        <div data-role="fieldcontain">
            <fieldset data-role="controlgroup">
            	<input type="checkbox" name="ship_to_this_address" id="ship_to_this_address" checked="checked" class="custom" />
            	<label for="ship_to_this_address"><?php echo lang('ship_to_this_address'); ?></label>
            </fieldset>
        </div>
        <?php 
            endif;
        ?>    
        </ul>
    
    	<button type="submit" id="btn-save-billing-form" data-theme="b"><?php echo lang('button_continue'); ?></button>
    </div>
</div>