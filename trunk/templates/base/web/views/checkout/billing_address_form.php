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
        <ol>
        <?php 
            if (!$is_logged_on) : 
        ?>
            <li>
                <?php echo draw_label(lang('field_customer_email_address'), null, 'billing_email_address', TRUE); ?>
                <input type="text" id="billing_email_address" name="billing_email_address" value="<?php echo $billing_email_address; ?>" />
            </li>
        <?php 
            endif; 
        ?>
        		
        <?php 
            if ((!$is_logged_on) && ($checkout_method == 'register')) : 
        ?>
            <li>
                <?php echo draw_label(lang('field_customer_password'), null, 'billing_password', TRUE); ?>
                <input type="password" id="billing_password" name="billing_password" />
            </li>
            
            <li>
                <?php echo draw_label(lang('field_customer_password_confirmation'), null, 'confirmation', TRUE); ?>
                <input type="password" id="confirmation" name="confirmation" />
            </li>
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

                foreach ($address_books as $address_book) :
                    $address[$address_book['address_book_id']] = address_format($address_book, ', ');
                endforeach;
                
                echo form_dropdown('sel_billing_address', $address, null, 'id="sel_billing_address"');
            ?>
            </li>
        <?php
            endif;
        ?>
        
        <div id="billingAddressDetails" style="display: <?php echo ($create_billing_address == FALSE) ? 'none' : ''; ?>">
        
        <?php 
            if (config('ACCOUNT_GENDER') > -1) :
        ?>
            <li>
                <?php echo draw_label(lang('field_customer_gender'), null, 'fake', (config('ACCOUNT_GENDER') > 0)); ?>
                <?php echo form_radio('billing_gender', 'm', (($billing_gender == 'm') ? TRUE : FALSE)) . lang('gender_male') . form_radio('billing_gender', 'f', (($billing_gender == 'f') ? TRUE : FALSE)) . lang('gender_female'); ?>
            </li>
        <?php 
            endif;
        ?>
          
            <li>
                <?php echo draw_label(lang('field_customer_first_name'), null, 'billing_firstname', TRUE); ?>
                <input type="text" id="billing_firstname" name="billing_firstname" value="<?php echo $billing_firstname; ?>" />
            </li>
            
            <li>
            	<?php echo draw_label(lang('field_customer_last_name'), null, 'billing_lastname', TRUE); ?>
                <input type="text" id="billing_lastname" name="billing_lastname" value="<?php echo $billing_lastname; ?>" />
            </li>
        
        <?php 
            if (config('ACCOUNT_COMPANY') > -1) :
        ?>
            <li>
            	<?php echo draw_label(lang('field_customer_company'), null, 'billing_company', (config('ACCOUNT_COMPANY') > 0)); ?>
                <input type="text" id="billing_company" name="billing_company" value="<?php echo $billing_company; ?>" />
            </li>
        <?php 
            endif;
        ?>
          
            <li>
            	<?php echo draw_label(lang('field_customer_street_address'), null, 'billing_street_address', TRUE); ?>
                <input type="text" id="billing_street_address" name="billing_street_address" value="<?php echo $billing_street_address; ?>" />
            </li>   
        
        <?php
            if (config('ACCOUNT_SUBURB') > -1) :
        ?>
            <li>
                <?php echo draw_label(lang('field_customer_suburb'), null, 'billing_suburb', (config('ACCOUNT_SUBURB') > 0)); ?>
                <input type="text" id="billing_suburb" name="billing_suburb" value="<?php echo $billing_suburb; ?>" />
            </li>   
        <?php
            endif;
        ?>
        
        <?php
            if (config('ACCOUNT_POST_CODE') > -1) :
        ?>
            <li>
                <?php echo draw_label(lang('field_customer_post_code'), null, 'billing_postcode', (config('ACCOUNT_POST_CODE') > 0)); ?>
                <input type="text" id="billing_postcode" name="billing_postcode" value="<?php echo $billing_postcode; ?>" />
            </li>  
        <?php
            endif;
        ?>
        
            <li>
            	<?php echo draw_label(lang('field_customer_city'), null, 'billing_city', TRUE); ?>
                <input type="text" id="billing_city" name="billing_city" value="<?php echo $billing_city; ?>" />
            </li>  
            
            <li>
            	<?php echo draw_label(lang('field_customer_country'), null, 'billing_country', TRUE); ?>
                <?php echo form_dropdown('billing_country', $countries, $billing_country_id, 'id="billing_country"'); ?>
            </li>
        
        <?php 
            if (config('ACCOUNT_STATE') > -1) :
        ?>
            <li id="li-billing-state">
            	<?php echo draw_label(lang('field_customer_state'), null, 'billing_state', (config('ACCOUNT_STATE') > 0)); ?>
            	
            <?php 
              if (count($states) > 0) :
            ?>
	            <?php echo form_dropdown('billing_state', $states, $billing_state, 'id="billing_state"'); ?>
            <?php 
              else :
            ?>
				<input type="text" id="billing_state" name="billing_state" value="<?php echo $billing_state; ?>" />
            <?php   
              endif;
            ?>
            
            </li>  
        <?php 
            endif;
        ?>
        
        <?php 
            if (config('ACCOUNT_TELEPHONE') > -1) :
        ?>
            <li>
                <?php echo draw_label(lang('field_customer_telephone_number'), null, 'billing_telephone', (config('ACCOUNT_TELEPHONE') > 0)); ?>
                <input type="text" id="billing_telephone" name="billing_telephone" value="<?php echo $billing_telephone; ?>" />
            </li>  
        <?php 
            endif;
        ?>
        
        <?php 
            if (config('ACCOUNT_FAX') > -1) :
        ?>
            <li>
                <?php echo draw_label(lang('field_customer_fax_number'), null, 'billing_fax', (config('ACCOUNT_FAX') > 0)); ?>
                <input type="text" id="billing_fax" name="billing_fax" value="<?php echo $billing_fax; ?>" />
            </li>  
        <?php 
            endif;
        ?>
        </div>
        
    <?php 
        if ($create_billing_address) :
    ?>
        <li>
            <?php echo form_checkbox('create_billing_address', 'on', $create_billing_address, 'id="create_billing_address"'); ?>
            <label for="create_billing_address"><?php echo lang('create_new_billing_address'); ?></label>
        </li>  
    <?php 
        endif;
    ?>
        
    <?php 
        if ($is_virtual_cart === FALSE) :
    ?>
        <li>
            <?php echo form_checkbox('ship_to_this_address', 'on', $ship_to_this_address); ?>
            <label for="ship_to_this_address"><?php echo lang('ship_to_this_address'); ?></label>
        </li>
    <?php 
        endif;
    ?>    
        </ol>
        
        <p align="right">
			<button type="submit" class="btn btn-small btn-small" id="btn-save-billing-form"><?php echo lang('button_continue'); ?></button>
        </p>
    </div>
</div>