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

<h1><?php echo lang('address_book_heading'); ?></h1>

<?php echo toc_validation_errors('address_book'); ?>

<div class="moduleBox addressBookBox">
    <h6><?php echo lang('primary_address_title'); ?></h6>
    
    <div class="content clearfix">
        <div><?php echo $primary_address_format; ?></div>
        <div class="primaryAddressTitle">
            <strong><?php echo lang('primary_address_title'); ?></strong><br /><img src="<?php echo image_url('arrow_south_east.gif'); ?>" />
        </div>
        <p><?php echo lang('primary_address_description') ?></p>
    </div>
</div>

<div class="moduleBox">
    <h6><?php echo lang('address_book_title'); ?></h6>
    
    <div class="content">
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <?php
            if (!empty($address_books)) :
                foreach($address_books as $address_book) :
        ?>
            <tr class="moduleRow">
                <td>
                    <strong><?php echo $address_book['firstname'] . ' ' . $address_book['lastname']; ?></strong>
                    <?php
                        if ($address_book['address_book_id'] == $default_address_id) :
                    ?>
                    <small><i><?php echo lang('primary_address_marker'); ?></i></small>
                    <?php
                        endif;
                    ?>
                </td>
                <td align="right">
                    <a href="<?php echo site_url('account/address_book/edit/' . $address_book['address_book_id']); ?>"><img src="<?php echo image_url('small_edit.gif'); ?>" title="<?php echo lang('button_edit'); ?>" /></a>
                    <a href="<?php echo site_url('account/address_book/delete/' . $address_book['address_book_id']); ?>"><img src="<?php echo image_url('small_delete.gif'); ?>" title="<?php echo lang('button_delete'); ?>" /></a>
                </td>
            </tr>
            <tr>
                <td colspan="2"><?php echo $address_book['format']; ?></td>
            </tr>
        <?php
                endforeach;
            endif;
        ?>
            
        </table>
    </div>
</div>

<div class="submitFormButtons">
    <div class="fr">
    <?php
        if (count($address_books) < config('MAX_ADDRESS_BOOK_ENTRIES')) :
    ?>
        <a href="<?php echo site_url('account/address_book/add'); ?>" class="button"><?php echo lang('button_add_address'); ?></a>
    <?php
        else :
        
        echo sprintf(lang('address_book_maximum_entries'), config('MAX_ADDRESS_BOOK_ENTRIES'));
        
        endif;
    ?>
    </div>
    <a href="<?php site_url('account'); ?>" class="button"><?php echo lang('button_back'); ?></a>
</div>