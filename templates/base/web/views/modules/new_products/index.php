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
    <h4 class="title"><?php echo lang('box_new_products_heading'); ?></h4>
    
    <div class="contents clearfix col3">
    <?php 
        foreach ($products as $product):
    ?>
        <div class="center">
            <div>
                 <a href="<?php echo site_url('product/' . $product['products_id']); ?>">
                      <img title="<?php echo $product['products_name']; ?>" alt="<?php echo $product['products_name']; ?>" src="<?php echo product_image_url($product['products_image'], 'thumbnails'); ?>" />
                 </a>
            </div>
            <div class="name"><a href="<?php echo site_url('product/' . $product['products_id']); ?>"><?php echo $product['products_name']; ?></a></div>
            <div class="short"><a href="<?php echo site_url('product/' . $product['products_id']); ?>"><?php echo $product['products_short_description']; ?></a></div>
            <div class="price"><a href="<?php echo site_url('product/' . $product['products_id']); ?>"><?php echo currencies_format($product['products_price']); ?></a></div>
            <div class="cart"><a href="<?php echo site_url('cart_add/' . $product['products_id']); ?>" class="btn btn-mini btn-info"><i class="icon-shopping-cart icon-white "></i> <?php echo lang('button_add_to_cart'); ?></a></div>
        </div>
    <?php 
        endforeach;
    ?>
    </div>
</div>