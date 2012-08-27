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

<h1><?php echo lang('new_products_heading');?></h1>

<div class="products-list">
	<ul data-role="listview" data-dividertheme="d">
    <?php 
        foreach($products as $product):
    ?>
  		<li>
            <a href="<?php echo $product['product_link'];?>">
                <img src="<?php echo product_image_url($product['product_image']);?>" />
                <h3><?php echo $product['product_name'];?></h3>
                <p><?php echo $product['product_name'];?></p>
                <div class="price"><?php echo $product['product_price'];?></div>
            </a>
  		</li>
    <?php
        endforeach;
    ?>
	</ul>
	
    <br />
    <br />
    <br />
    <br />
    <br />

    <div class="paginate">
        <?php  echo $link;?>
    </div>
</div>