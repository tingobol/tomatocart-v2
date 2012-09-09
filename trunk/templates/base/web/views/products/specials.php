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

<h1><?php echo lang('specials_heading'); ?></h1>

<?php 
    if (sizeof($products) > 0): 
?>
<div id="specials" class="content">
    <?php 
        foreach($products as $product):
    ?>
    <div class="product col3">
        <p class="name"><a href="<?php echo site_url('product/' . $product['products_id']); ?>"><?php echo $product['product_name']; ?></a></p>
        <a href="<?php echo site_url('product/' . $product['products_id']); ?>"><img src="<?php echo product_image_url($product['product_image']); ?>" title="<?php echo $product['product_name']; ?>" alt="<?php echo $product['product_name']; ?>"/></a>     
        <p class="price"><s><?php echo currencies_format($product['product_price']); ?></s><span class="special"><?php echo currencies_format($product['special_price']); ?></span></p>
        <a class="button small"><?php echo lang('button_add_to_cart'); ?></a>
    </div>
    <?php 
        endforeach;
    ?>
    <div class="paginate">
        <?php echo $links; ?>
    </div>
    <div class="clear"></div>
</div>
<?php 
    endif; 
?>
  