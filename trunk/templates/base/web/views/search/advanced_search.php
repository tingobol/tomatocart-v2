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

<h1><?php echo lang('search_heading'); ?></h1>

<?php echo toc_validation_errors('search'); ?>

<form name="search" action="<?php echo site_url('search'); ?>" method="post">
    <div class="moduleBox searchBox">
        <h6><?php echo lang('search_criteria_title'); ?></h6>
        
        <div class="content">
            <input type="text" name="keywords" class="keywords" value="<?php set_value('keywords'); ?>" />
        </div>
    </div>
    
    <div class="submitFormButtons clearfix">
        <button class="fr button"><?php echo lang('button_search'); ?></button>
    </div>
    
    <div class="moduleBox">
        <h6><?php echo lang('advanced_search_heading'); ?></h6>
        <div class="content">
            <ul>
                <li>
                    <label class="field_label"><?php echo lang('field_search_categories'); ?></label>
                    <?php
                        echo form_dropdown('cPath', $categories);
                    ?>
                </li>
                <li>
                    <input type="checkbox" name="recursive" id="recursive" value="1" <?php echo set_checkbox('recursive', '1', TRUE);?> />
                    <label for="recursive"><?php echo lang('field_search_recursive'); ?></label>
                </li>
                <li>
                    <label class="field_label"><?php echo lang('field_search_manufacturers'); ?></label>
                    <select name="manufacturers">
                        <?php
                            foreach($manufacturers as $manufacturer) :
                        ?>
                        <option value="<?php echo $manufacturer['id']; ?>" <?php echo set_select('manufacturers', $manufacturer['id']); ?>><?php echo $manufacturer['text']; ?></option>
                        <?php
                            endforeach;
                        ?>
                    </select>
                </li>
                <li>
                    <label for="pfrom" class="field_label"><?php echo lang('field_search_price_from'); ?></label>
                    <input type="text" name="pfrom" id="pfrom" value="<?php echo set_value('pfrom'); ?>" />
                </li>
                <li>
                    <label for="pto" class="field_label"><?php echo lang('field_search_price_to'); ?></label>
                    <input type="text" name="pto" id="pto" value="<?php echo set_value('pto'); ?>" />
                </li>
            </ul>
            
        </div>
    </div>
</form>