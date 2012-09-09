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

<h1><?php echo lang('shopping_cart_heading'); ?></h1>

<?php 
    if ($has_contents == TRUE) :
?>
<form name="shopping_cart" action="<?php echo site_url('cart_update'); ?>" method="post">
    <div class="box">
		<h6 class="title"><?php echo lang('shopping_cart_heading'); ?></h6>
    
        <div class="content">
            <table border="0" width="100%" cellspacing="5" cellpadding="2">
            
            <?php
                $_cart_date_added = null;
                
                foreach ($products as $product_id => $product) :
                
                
                  if ($product['date_added'] != $_cart_date_added):
                    $_cart_date_added = $product['date_added'];
            ?>
                <tr>
                	<td colspan="4"><?php echo sprintf(lang('date_added_to_shopping_cart'), $product['date_added']); ?></td>
                </tr>
            <?php 
                endif;
            ?>
                <tr>
                    <td valign="top" width="30" align="center">
                        <!--Delete Icon-->
                        <a href="<?php echo site_url('cart_delete/' . encode_product_id_string($product['id'])); ?>">
                        	<img border="0" title="<?php echo lang('button_delete');?>" alt="<?php echo lang('button_delete');?>" src="<?php echo image_url('small_delete.gif');?>">
                        </a>
                        <!--END: Delete Icon-->
                    </td>
                    <td valign="middle">
                        <a href="<?php echo site_url('product/' . (int) $product['id']);?>"><?php echo $product['name']?></a>
                    <?php 
                        if ( (config('STOCK_CHECK') == '1') && ($product['in_stock'] === FALSE) ) :
                    ?>
                          <span class="markProductOutOfStock"><?php echo config('STOCK_MARK_PRODUCT_OUT_OF_STOCK') ; ?></span>
                    <?php 
                        endif;
                        if (isset($product['variants']) && !empty($product['variants'])) :
                            foreach ($product['variants'] as $variants):  
                    ?>
                    	<br />- <?php echo $variants['groups_name'];?> : <?php echo $variants['values_name'];?>
                    <?php
                            endforeach;
                        endif;
                    ?>
                    </td>
                    <td valign="top">
						<input type="text" style="width: 20px" size="4" id="product_<?php echo $product['id']; ?>" value="<?php echo $product['quantity'];?>" name="product[<?php echo encode_product_id_string($product['id']); ?>]" />
                    </td>
                    <td valign="top" align="right"><?php echo currencies_format($product['price']); ?></td>
                </tr>
            <?php
                endforeach;
            ?>
            </table>
        </div>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <?php
            foreach ($order_totals as $module) :
        ?>
			<tr>
            	<td align="right"><?php echo $module['title']; ?></td>
				<td align="right" width="100"><?php echo $module['text']; ?>&nbsp;</td>
			</tr>
        <?php
            endforeach;
        ?>
        </table>
        
<?php
    if ( (config('STOCK_CHECK') == '1') && ($has_stock === FALSE) ) :
        if (config('STOCK_ALLOW_CHECKOUT') == '1') :
?>
          <p class="stockWarning" align="center"><?php echo sprintf(lang('products_out_of_stock_checkout_possible'), config('STOCK_MARK_PRODUCT_OUT_OF_STOCK')); ?></p>
<?php 
        else :
?>
          <p class="stockWarning" align="center"><?php echo sprintf(lang('products_out_of_stock_checkout_not_possible'), config('STOCK_MARK_PRODUCT_OUT_OF_STOCK')); ?></p>
<?php 
        endif;
    endif;
?>
    </div>
    
    <div class="submitFormButtons">
        <span style="float: right;"><a class="button" href="<?php echo site_url('checkout'); ?>"><?php echo lang('button_checkout');?></a></span>
        
        <a href="<?php echo base_url(); ?>" class="button"><?php echo lang('button_continue_shopping'); ?></a>
        
        <span><input type="submit" class="button" value="<?php echo lang('button_update_cart'); ?>" /></span>
    </div>
</form>
<?php 
    else : 
?>

<p><?php echo lang('shopping_cart_empty'); ?></p>

<div class="submitFormButtons" style="text-align: right;">
	<a href="<?php echo base_url(); ?>" class="button"><?php echo lang('button_continue'); ?></a>
</div>

<?php 
    endif; 
?>