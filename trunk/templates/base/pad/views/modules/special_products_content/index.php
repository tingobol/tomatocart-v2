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

<div id="specials" class="module-box">
    <div data-role="listview" data-inset="true" data-theme="c" data-dividertheme="f">
        <li data-role="list-divider"><?php echo $title; ?></li>
        <?php 
            foreach($products as $product) :
        ?>
        <li class="product col3">
            <div><a href="<?php echo $product['product_link']; ?>"><img border="0" title="<?php echo $product['products_name']; ?>" alt="<?php echo $product['products_name']; ?>" src="<?php echo product_image_url($product['products_image']); ?>"></a></div>
            <div><a href="<?php echo $product['product_link']; ?>"><?php echo $product['products_name']; ?></a></div>
            <div><s><?php echo $product['products_price']; ?></s>&nbsp;&nbsp;<span><?php echo $product['special_price'];?></span></div>
            <div><a href="<?php echo site_url('cart_add/' . $product['product_id']);?>" data-role="button" data-ajax="false"><span><?php echo lang('button_add_to_cart'); ?></span></a></div>
        </li>
        <?php 
            endforeach;
        ?>
    </div>
    <div style="clear: both"></div>
</div>