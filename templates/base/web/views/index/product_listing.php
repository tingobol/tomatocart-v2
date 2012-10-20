<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
*/
?>

<h1><?php echo $title; ?></h1>

<?php 
    if (count($products) > 0):
?>
    <div class="products-list">
        <?php 
            foreach($products as $product):
        ?>
        <div class="row-fluid clearfix">
            <a class="image span2" href="<?php echo site_url('product/' . $product['products_id']); ?>">
    			<img alt="<?php echo $product['product_name']; ?>" title="<?php echo $product['product_name']; ?>" src="<?php echo product_image_url($product['product_image']); ?>" />
            </a>
            <div class="info span6">
                <a href="<?php echo site_url('product/' . $product['products_id']); ?>"><?php echo $product['product_name']; ?></a>
                <p class="description">
                    <?php echo $product['short_description']; ?>
                </p>
            </div>  
            <span class="price center span2"><?php echo currencies_format($product['product_price']); ?></span>
            <div class="buttons span2">
                <a class="btn btn-small btn-small btn-info" href="<?php echo site_url('cart_add/' . $product['products_id']); ?>">
                	<i class="icon-shopping-cart icon-white "></i> 
                	<?php echo lang('button_add_to_cart'); ?>
                </a><br />
                <a class="wishlist" href="javascript:void(0);"><?php echo lang('add_to_wishlist'); ?></a><br />
                <a class="compare" href="javascript:void(0);"><?php echo lang('add_to_compare'); ?></a>
            </div>
        </div>
        <?php
            endforeach;
        ?>
    </div>
    
    <div class="pagination clearfix">
    	<?php  echo $links; ?>
    </div>
<?php 
    else:
?>
	<p><?php echo lang('no_products_in_category'); ?></p>
<?php 
    endif;
?>