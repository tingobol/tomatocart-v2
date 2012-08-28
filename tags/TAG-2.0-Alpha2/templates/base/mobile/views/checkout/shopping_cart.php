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
<h1><?php echo $title; ?></h1>

<form name="shopping_cart" action="<?php echo site_url('checkout/cart_update'); ?>" method="post">
<div class="shoppingcart-list">
	<ul data-role="listview">
    <?php
      foreach ($products as $product):
    ?>
  		<li>
  		  <img src="/images/products/thumbnails/<?php echo $product['image'];?>" />
  			<div class="btn-delete">
	      <a href="<?php echo site_url('checkout/cart_delete/' . $product['id']); ?>" data-role="button" data-icon="delete" data-iconpos="notext" data-ajax="false"><?php echo lang('button_delete');?></a>
	      </div>
	      <div class="name">
	      <a href="<?php echo site_url('product/' . $product['id']);?>"><?php echo $product['name']?></a>
    		<?php 
    		  if(1):
    		?>
          <span class="markProductOutOfStock">***</span>
    		<?php 
    		  endif; 
    		?>
		    <?php 
        if (isset($product['variants']) && !empty($product['variants'])):
          foreach ($product['variants'] as $variants):
        ?>
          <br />- <?php echo $variants['groups_name'];?> : <?php echo $variants['values_name'];?>
        <?php
          endforeach;
        endif;
        ?>
        </div>
        <div class="quantity"><input id="product_<?php echo $product['id']; ?>" type="text" name="product[<?php echo $product['id']; ?>]" value="<?php echo $product['quantity'];?>" data-inline="true" /></div>
        <div class="price"><?php echo $product['price']; ?></div>
  		</li>
    <?php
      endforeach;
    ?>
    	<li>
    		<div class="order-totals">
        <?php
          foreach ($order_totals as $module):
        ?>
        	<div><?php echo $module['title']; ?><span class="total"><?php echo $module['text']; ?></span></div>
        <?php 
          endforeach;
        ?>
        </div>
    	</li>
	</ul>
</div>
<div data-role="fieldcontain">   
  <div class="ui-grid-b">
  	<div class="ui-block-a"><a data-role="button"><?php echo lang('button_update_cart'); ?></a></div>
  	<div class="ui-block-b"><a data-role="button" data-theme="e" href="<?php echo base_url(); ?>" data-ajax="false"><?php echo lang('button_continue_shopping');?></a></div>
  	<div class="ui-block-c"><a data-role="button" data-theme="b" href="<?php echo site_url('checkout'); ?>"><?php echo lang('button_checkout');?></a></div>
  </div>
</div>
</form>
