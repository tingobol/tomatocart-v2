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

<div class="box">
    <h4 class="title"><?php echo $title; ?></h4>
    
    <div class="contents">
        <form method="get" action="<?php echo current_url(); ?>" name="currencies">
            <select onchange="this.form.submit();" id="currency" name="currency">
            <?php 
            foreach ($currencies as $currency):
                if ($currency['code'] == $currency_code):
            ?>
            	<option selected="selected" value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
            <?php 
              else :
            ?>
            	<option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
            <?php 
              endif;
            endforeach;
            ?>
            </select>
        </form>
    </div>
</div>