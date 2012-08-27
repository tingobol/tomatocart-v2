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

<h1><?php echo (isset($address_book_id) && is_numeric($address_book_id)) ? lang('address_book_edit_entry_heading') : lang('address_book_add_entry_heading'); ?></h1>

<?php echo toc_validation_errors('address_book'); ?>

<form name="address_book" action="<?php echo site_url('account/address_book/save'); ?>" method="post">
    <div class="moduleBox">
        <h6><em><?php echo lang('form_required_information'); ?></em><?php echo lang('address_book_new_address_title'); ?></h6>
        
        <div class="content">
            <ul>
                <?php
                    if (config('ACCOUNT_GENDER') > -1) :
                ?>
                <li>
                    <label class="field_label" for="gender1"><?php echo lang('field_customer_gender') . ((config('ACCOUNT_GENDER') > 0) ? '<em>*</em>' : ''); ?></label>
                    <input type="radio" value="m" id="gender1" name="gender" <?php echo set_radio('gender', 'm', isset($gender) && $gender == 'm'); ?> /><label for="gender1"><?php echo lang('gender_male'); ?></label>
                    <input type="radio" value="f" id="gender2" name="gender" <?php echo set_radio('gender', 'f', isset($gender) && $gender == 'f'); ?> /><label for="gender2"><?php echo lang('gender_female'); ?></label>
                </li>
                <?php
                    endif;
                ?>
                <li>
                    <label class="field_label" for="firstname"><?php echo lang('field_customer_first_name'); ?><em>*</em></label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo (isset($firstname)) ? $firstname : set_value('firstname'); ?>" />
                </li>
                <li>
                    <label class="field_label" for="lastname"><?php echo lang('field_customer_last_name'); ?><em>*</em></label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo isset($lastname) ? $lastname : set_value('lastname'); ?>" />
                </li>
                <?php
                    if (config('ACCOUNT_COMPANY') > -1) :
                ?>
                <li>
                    <label class="field_label" for="company"><?php echo lang('field_customer_company') . (config('ACCOUNT_COMPANY') > 0 ? '<em>*</em>' : '') ;?></label>
                    <input type="text" id="company" name="company" value="<?php echo isset($company) ? $company : set_value('company'); ?>" />
                </li>
                <?php
                    endif;
                ?>
                <li>
                    <label class="field_label" for="street_address"><?php echo lang('field_customer_street_address'); ?><em>*</em></label>
                    <input type="text" id="street_address" name="street_address" value="<?php echo isset($street_address) ? $street_address : set_value('street_address'); ?>" />
                </li>
                <?php
                    if (config('ACCOUNT_SUBURB') > -1) :
                ?>
                <li>
                    <label class="field_label" for="suburb"><?php echo lang('field_customer_suburb') . (config('ACCOUNT_SUBURB') > 0 ? '<em>*</em>' : '') ;?></label>
                    <input type="text" id="suburb" name="suburb" value="<?php echo isset($suburb) ? $suburb : set_value('suburb'); ?>" />
                </li>
                <?php
                    endif;
                    
                    if (config('ACCOUNT_POST_CODE') > -1) :
                ?>
                <li>
                    <label class="field_label" for="postcode"><?php echo lang('field_customer_post_code') . (config('ACCOUNT_POST_CODE') > 0 ? '<em>*</em>' : '') ;?></label>
                    <input type="text" id="postcode" name="postcode" value="<?php echo isset($postcode) ? $postcode : set_value('postcode'); ?>" />
                </li>
                <?php
                    endif;
                ?>
                <li>
                    <label class="field_label" for="city"><?php echo lang('field_customer_city'); ?><em>*</em></label>
                    <input type="text" id="city" name="city" value="<?php echo isset($city) ? $city : set_value('city'); ?>" />
                </li>
                <li>
                    <label class="field_label" for="country"><?php echo lang('field_customer_country'); ?><em>*</em></label>
                    <select id="country" name="country">
                <?php
                    if (isset($countries) && !empty($countries)) :
                        foreach($countries as $country) :
                ?>
                        <option value="<?php echo $country['id']; ?>" <?php echo set_select('country', $country['id'], isset($country_id) && ($country_id == $country['id'])); ?>><?php echo $country['name']; ?></option>
                <?php
                        endforeach;
                    
                    else :
                ?>
                        <option value=""><?php echo lang('pull_down_default'); ?></option>
                <?php
                    endif;
                ?>
                    </select>
                </li> 
                <?php
                    if ((config('ACCOUNT_STATE') > -1) && isset($states) && !empty($states)) :
                ?>
                <li>
                    <label class="field_label" for="state"><?php echo lang('field_customer_state') . (config('ACCOUNT_STATE') > 0 ? '<em>*</em>' : '') ;?></label>
                    
                    <select id="state" name="state">
                    
                        <?php
                            foreach($states as $state) :
                        ?>
                        <option value="<?php echo $state['id']; ?>" <?php echo set_select('state', $state['id'], isset($state_code) && ($state_code == $state['id'])); ?>><?php echo $state['text']; ?></option>
                        <?php
                            endforeach;
                        ?>
                    
                    </select>
                </li>
                        
                <?php
                    endif;
                    
                    if (config('ACCOUNT_TELEPHONE') > -1) :
                ?>
                <li>
                    <label class="field_label" for="telephone"><?php echo lang('field_customer_telephone_number') . (config('ACCOUNT_TELEPHONE') > 0 ? '<em>*</em>' : '') ;?></label>
                    <input type="text" id="telephone" name="telephone" value="<?php echo isset($telephone) ? $telephone : set_value('telephone'); ?>" />
                </li>
                <?php
                    endif;
                ?>
                <?php
                    if (config('ACCOUNT_FAX') > -1) :
                ?>
                <li>
                    <label class="field_label" for="fax"><?php echo lang('field_customer_fax_number') . (config('ACCOUNT_FAX') > 0 ? '<em>*</em>' : '') ;?></label>
                    <input type="text" id="fax" name="fax" value="<?php echo isset($fax) ? $fax : set_value('fax'); ?>" />
                </li>
                <?php
                    endif;
                ?>
                <?php
                    if (isset($display_primary) && $display_primary===TRUE) :
                ?>
                <li>
                    <input type="checkbox" value="1" id="primary" name="primary" <?php echo set_checkbox('primary', '1'); ?> />
                    <label for="primary"><?php echo lang('set_as_primary'); ?></label>
                </li>
                <?php
                    endif;
                ?>
                
                <?php
                    if (isset($address_book_id) && is_numeric($address_book_id)) :
                ?>
                      <input type="hidden" name="address_book_id" value="<?php echo $address_book_id; ?>" />
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#country').bind('change', function() {
            $.ajax({
              type: 'post',
              cache: 'false',
              url: '<?php echo site_url('account/address_book/get_states') ?>',
              data: {country_id: $('#country').val()},
              dataType: 'json',
              success: function(states) {
                 $('#state').empty();
                 
                 $.each(states, function(index, state) {
                    $('#state').append('<option value="' + state.id + '">' + state.text + '</option>');
                 });
              }
            });
        });
    });
</script>