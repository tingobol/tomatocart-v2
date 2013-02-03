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

<h1><?php echo lang('new_products_heading'); ?></h1>

<?php 
    if (sizeof($products) > 0): 
?>
<div class="module-box col3 clearfix">
<?php 
    foreach($products as $product):
?>
    <div class="center">
        <a href="<?php echo site_url('product/' . $product['products_id']); ?>">
        	<img alt="<?php echo $product['product_name']; ?>" title="<?php echo $product['product_name']; ?>" src="<?php echo product_image_url($product['product_image']); ?>">
        </a>
        <div class="info">
			<a href="<?php echo site_url('product/' . $product['products_id']); ?>"><?php echo $product['product_name']; ?></a>
        	<p class="description">
                <?php echo $product['short_description']; ?>
          	</p>
        </div>  
        <span class="price"><?php echo currencies_format($product['product_price']); ?></span>
        <div class="buttons">
          	<a class="btn btn-mini btn-info" href="<?php echo site_url('cart_add/' . $product['products_id']); ?>">
            	<i class="icon-shopping-cart icon-white "></i> 
            	<?php echo lang('button_add_to_cart'); ?>
          	</a>
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
    endif; 
?>