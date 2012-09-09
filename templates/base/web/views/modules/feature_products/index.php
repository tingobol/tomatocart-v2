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
    <h4 class="title"><?php echo lang('feature_products_title'); ?></h4>
    
    <div class="contents">
    <?php 
        foreach ($products as $product):
    ?>
        <div class="product col3">
            <div>
                <a href="<?php echo site_url('product/' . $product['products_id']); ?>">
					<img title="<?php echo $product['products_name']; ?>" alt="<?php echo $product['products_name']; ?>" src="<?php echo product_image_url($product['products_image']); ?>">
                </a>
            </div>
            <div><a href="<?php echo site_url('product/' . $product['products_id']); ?>"><?php echo $product['products_name']; ?></a></div>
            <div><a href="<?php echo site_url('product/' . $product['products_id']); ?>"><?php echo currencies_format($product['products_price']); ?></a></div>
            <div><a href="<?php echo site_url('cart_add/' . $product['products_id']); ?>" class="button small"><span><?php echo lang('button_add_to_cart'); ?></span></a></div>
        </div>
    <?php 
        endforeach;
    ?>
    
    <div style="clear: both">&nbsp;</div>
    </div>
</div>