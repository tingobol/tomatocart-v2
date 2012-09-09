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
                if ($is_logged_on && count($address_books) > 0) :
            ?>
    		<li>
                <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
                <?php echo '<b>' . lang('please_select') . '</b><br />'; ?>
                </div>
                
                <p style="margin-top: 0px;"><?php echo lang('choose_shipping_address'); ?></p>
            </li>    
            <li style="margin-bottom: 10px">
            <?php
                $address = array();
                foreach ($address_books as $address_book) {
                    $address[$address_book['address_book_id']] = address_format($address_book, ', ');
                }
                
                echo form_dropdown('sel_shipping_address', $address);
            ?>
            </li>
            <?php
                endif;
            ?>

            <div id="shippingAddressDetails" style="display: <?php echo ($create_shipping_address == FALSE) ? 'none' : ''; ?>">
            <?php 
                if (config('ACCOUNT_GENDER') > -1) :
            ?>
                <div data-role="fieldcontain">
                    <fieldset data-role="controlgroup" data-type="horizontal" data-role="fieldcontain">
						<legend><?php echo lang('field_customer_gender');?><span class="required">*</span></legend>
                    	<input type="radio" name="shipping_gender" id="gender1" value="m" <?php echo (($shipping_gender == 'm') ? 'checked="checked"' : ''); ?> />
                    	<label for="gender1"><?php echo lang('gender_male'); ?></label>
                    
                    	<input type="radio" name="shipping_gender" id="gender2" value="f" <?php echo (($shipping_gender == 'f') ? 'checked="checked"' : ''); ?> />
                    	<label for="gender2"><?php echo lang('gender_female'); ?></label>
                	</fieldset>        
                </div>   
            <?php 
                endif;
            ?>
    
        		<div data-role="fieldcontain">            
                    <?php echo draw_label(lang('field_customer_first_name'), null, 'shipping_firstname', TRUE); ?>
                	<input type="text" name="shipping_firstname" id="shipping_firstname" value="<?php echo $shipping_firstname;?>" />
        		</div>
        	
            	<div data-role="fieldcontain">            
                    <?php echo draw_label(lang('field_customer_last_name'), null, 'shipping_lastname', TRUE); ?>
            	  	<input type="text" name="shipping_lastname" id="shipping_lastname" value="<?php echo $shipping_lastname;?>" />
            	</div>
    
            <?php 
                if (config('ACCOUNT_COMPANY') > -1) :
            ?>
                <div data-role="fieldcontain">            
                    <?php echo draw_label(lang('field_customer_company'), null, 'shipping_company', (config('ACCOUNT_COMPANY') > 0)); ?>
                	<input type="text" name="shipping_company" id="shipping_company" value="<?php echo $shipping_company;?>" />
                </div>
            <?php 
                endif;
            ?>
            
            	<div data-role="fieldcontain">            
                    <?php echo draw_label(lang('field_customer_street_address'), null, 'shipping_street_address', TRUE); ?>
                	<input type="text" name="shipping_street_address" id="shipping_street_address" value="<?php echo $shipping_street_address;?>" />
            	</div>
            
            <?php
                if (config('ACCOUNT_SUBURB') > -1) :
            ?>
                <div data-role="fieldcontain">            
                    <?php echo draw_label(lang('field_customer_suburb'), null, 'shipping_suburb', (config('ACCOUNT_SUBURB') > 0)); ?>
                	<input type="text" name="shipping_suburb" id="shipping_suburb" value="<?php echo $shipping_suburb;?>" />
                </div>
            <?php
                endif;
            ?>
    
            <?php
              if (config('ACCOUNT_POST_CODE') > -1) :
            ?>
            	<div data-role="fieldcontain">            
                    <?php echo draw_label(lang('field_customer_post_code'), null, 'shipping_postcode', (config('ACCOUNT_POST_CODE') > 0)); ?>
            	  	<input type="text" name="shipping_postcode" id="shipping_postcode" value="<?php echo $shipping_postcode;?>" />
            	</div>
            <?php
              endif;
            ?>
    
            	<div data-role="fieldcontain">            
                    <?php echo draw_label(lang('field_customer_city'), null, 'shipping_city', TRUE); ?>
                    <input type="text" name="shipping_city" id="shipping_city" value="<?php echo $shipping_city;?>" />
            	</div>
    	
                <div data-role="fieldcontain">     
                    <?php echo draw_label(lang('field_customer_country'), null, 'shipping_country', TRUE); ?>
                    <?php echo form_dropdown('shipping_country', $countries, $shipping_country_id, 'id="shipping_country"');?>
                </div>
      
            <?php 
                if (config('ACCOUNT_STATE') > -1) :
            ?>
                <div data-role="fieldcontain" id="li-shipping-state">
	                <?php echo draw_label(lang('field_customer_state'), null, 'shipping_state', (config('ACCOUNT_STATE') > 0)); ?>
                    <?php 
                        if (count($states) > 0) :
                    ?>
	                    <?php echo form_dropdown('shipping_state', $states, $shipping_state, 'id="shipping_state"');?>
                    <?php 
                        else :
                    ?>
						<input type="text" id="shipping_state" name="shipping_state" value="<?php echo $shipping_state;?>" />
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
                    <?php echo draw_label(lang('field_customer_telephone_number'), null, 'shipping_telephone', (config('ACCOUNT_TELEPHONE') > 0)); ?>
                    <input type="text" name="shipping_telephone" id="shipping_telephone" value="<?php echo $shipping_telephone;?>" />
                </div>
            <?php 
                endif;
            ?>
            
            <?php 
                if (config('ACCOUNT_FAX') > -1) :
            ?>
                <div data-role="fieldcontain">     
                    <?php echo draw_label(lang('field_customer_fax_number'), null, 'shipping_fax', (config('ACCOUNT_FAX') > 0)); ?>
                    <input type="text" name="shipping_fax" id="shipping_fax" value="<?php echo $shipping_fax;?>" />
                </div>
            <?php 
                endif;
            ?>
    		</div>
    
    		<li style="height:10px;line-height:10px">&nbsp;</li>
            <?php 
                if ($is_logged_on) :
            ?>
              	<div data-role="fieldcontain">
                    <fieldset data-role="controlgroup">
                    	<input type="checkbox" name="create_shipping_address" id="create_shipping_address" checked="checked" class="custom" />
                    	<label for="create_shipping_address"><?php echo lang('create_new_shipping_address');?></label>
                    </fieldset>
              	</div>
            <?php 
                endif;
            ?>
    
    		<button type="submit" class="small button" id="btn-save-shipping-form" data-theme="b"><?php echo lang('button_continue'); ?></button>
    	</ul>
    </div>
</div>