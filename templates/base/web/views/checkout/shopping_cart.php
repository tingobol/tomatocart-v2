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
<form name="shopping_cart" action="<?php echo site_url('cart_update'); ?>" method="post" class="clearfix">
    <div id="shopping-cart" class="module-box">
		<h6 class="title">
			<span class="icon"><i class="icon-shopping-cart"></i></span>		
            <span><?php echo lang('shopping_cart_heading'); ?></span>
        </h6>
    
        <div class="content clearfix">
        	<table class="" width="100%">
        		<thead>
        			<tr>
        				<th colspan="2" class="product"><?php echo lang('table_heading_product'); ?></th>
        				<th colspan="2"><?php echo lang('table_heading_quantity'); ?></th>
        				<th class="price"><?php echo lang('table_heading_price'); ?></th>
        				<th class="total"><?php echo lang('table_heading_total'); ?></th>
        			</tr>
        		</thead>
        		<tbody>
                <?php
                    foreach ($products as $product_id => $product) :
                ?>
                    <tr>
                    	<td class="image">
                            <a href="<?php echo site_url('product/' . $product['id']); ?>">
    							<img alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" src="<?php echo product_image_url($product['image'], 'mini'); ?>" alt="default thumb" class="thumb" />
                            </a>
                    	</td>
                        <td class="product">
                            <a class="name" href="<?php echo site_url('product/' . get_product_id($product['id']));?>"><?php echo $product['name']?></a>
                            <span class="sku"><?php echo lang('field_sku') . ' ' . $product['sku']; ?></span>
                            <?php 
                                if ( (config('STOCK_CHECK') == '1') && ($product['in_stock'] === FALSE) ) :
                            ?>
                            <span class="markProductOutOfStock"><?php echo config('STOCK_MARK_PRODUCT_OUT_OF_STOCK') ; ?></span>
                            <?php 
                                endif;
                            ?>
                            
                            <?php 
                                if (isset($product['variants']) && !empty($product['variants'])) :
                            ?>
                            	<ul class="variants">
                                <?php 
                                        foreach ($product['variants'] as $variants):  
                                ?>
                        			<li>- <?php echo $variants['groups_name'];?> : <?php echo $variants['values_name'];?></li>
                                <?php
                                        endforeach;
                                ?>
    							</ul>                        
                            <?php 
                                endif;
                            ?>
                            
                            <?php 
                                if (isset($products['error'])) {
                            ?>
                            <br /><span class="markProductError"><?php echo $products['error']; ?></span>
                            <?php 
                                }
                            ?>
                        </td>
                        <td class="quantity">
    						<input type="text" style="width: 20px" size="4" id="product[<?php echo $product_id; ?>]" value="<?php echo $product['quantity'];?>" name="product[<?php echo $product_id; ?>]" />
                        </td>
                        <td class="action">
                            <?php 
                                if (is_numeric($product['id'])) {
                                    $url = site_url('cart_delete/' . $product['id']);
                                } else {
                                    $variants = get_product_variants_string($product['id']);
                                    
                                    $url = site_url('cart_delete/' . get_product_id($product['id']) . '/' . $variants);
                                }
                            ?>
                            <a class="btn btn-mini" href="<?php echo $url; ?>"><i title="<?php echo lang('button_delete');?>" class="icon-trash"></i></a>
                        </td>
                        <td class="price"><?php echo currencies_display_price($product['final_price'], $product['tax_class_id']); ?></td>
                        <td class="total"><?php echo currencies_display_price($product['final_price'], $product['tax_class_id'], $product['quantity']); ?></td>
                    </tr>
                <?php
                    endforeach;
                ?>
            	</tbody>
            </table>
            <ul class="totals">
            <?php
                foreach ($order_totals as $module) :
            ?>
    			<li>
                	<label><?php echo $module['title']; ?></label>
    				<span><?php echo $module['text']; ?>&nbsp;</span>
    			</<li>
            <?php
                endforeach;
            ?>
            </ul>
        </div>
        <style>
            .totals {
                float: right;
                margin: 10px 0;
                padding-top: 10px;
                border-top: 1px solid #DFDFDF;
            }
            .totals, .totals label {
				font-weight: 700;
				text-indent: 5px;
            }
            .totals label {
            	display: inline-block;
            	width: 170px;
            }
            .totals span {
            	display: inline-block;
            	width: 90px;
            	text-align: right;
            }
            ul.products-list li {
            	padding: 0;
            }
            ul.variants {
            	font-size: 11px;
            	color: #707070;
            	margin-left: 5px;
            }
            ul.variants li {
            	border: none;
            	padding: 0px;
            }
            #shopping-cart {
            	border: none;
            	padding: 0;
            }
            #shopping-cart h6 {
                background-color: #EFEFEF;
                background-image: -moz-linear-gradient(center top , #FDFDFD 0%, #EAEAEA 100%);
                border-top: 1px solid #CDCDCD;
                border-left: 1px solid #CDCDCD;
                border-right: 1px solid #CDCDCD;
                border-radius: 3px 3px 0 0;
                margin: 0;
                color: #666666;
                font-size: 12px;
                font-weight: bold;
                margin: 0;
                text-shadow: 0 1px 0 #FFFFFF;
            }
            #shopping-cart span.icon {
                border-right: 1px solid #CDCDCD;
                opacity: 0.7;
                padding: 9px 10px 7px 11px;
                display: inline-block;
            }
            #shopping-cart span.title {
                border-right: 1px solid #FFFFFF;
            }
            #shopping-cart ul.list {
            	width: auto;
            }
            ul.list li div {
            	padding: 12px;
            }
            #shopping-cart table thead th {
                background-color: #EFEFEF;
                background-image: -moz-linear-gradient(center top , #FDFDFD 0%, #EAEAEA 100%);
                border-bottom: 1px solid #CDCDCD;
                height: 36px;
                color: #666666;
                font-size: 13px;
                font-weight: bold;
                text-shadow: 0 1px 0 #FFFFFF;
            }
            #shopping-cart table {
                border-top: 1px solid #CDCDCD;
                border-left: 1px solid #CDCDCD;
                border-right: 1px solid #CDCDCD;
            }
            #shopping-cart table tbody td {
                text-align: center;
                padding: 10px;
                border-bottom: 1px solid #CDCDCD;
            	vertical-align: top;
            }
            #shopping-cart table td.image {
            	width: 80px;
            }
            #shopping-cart table td.product {
            	text-align: left;
            }    
            #shopping-cart table td.product .name{
            	font-weight: bold;
            }
            #shopping-cart table td.product .sku{
            	display: block;
            	color: #707070;
            	font-size: 12px;
            	height: 18px;
            	line-height: 18px;
            	margin-bottom: 5px;
            }         
            #shopping-cart table td.quantity {
            	width: 40px;
            	padding-right: 0;
            }
            #shopping-cart table .price {
            	width: 90px;
            	text-align: right;
            	padding-right: 0px;
            }
            #shopping-cart table .total {
            	width: 100px;
            	font-weight: bold;
            	text-align: right;
            	padding-right: 10px;
            }
            #shopping-cart table .action {
            	width: 32px;
            	padding-left: 0px;
            }
            #shopping-cart table th.product {
            	text-align: left;
            	text-indent: 30px;
            }
        </style>
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
    
    <div class="row-fluid submitFormButtons">
        <div class="span4"><a href="<?php echo base_url(); ?>" class="btn btn-small btn-info"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue_shopping'); ?></a></div>
        
        <div class="span5"><button type="submit" class="btn btn-small btn-info"><i class="icon-refresh icon-white"></i> <?php echo lang('button_update_cart'); ?></button></div>

        <div class="span3"><a class="btn btn-small btn-small btn-info pull-right" href="<?php echo site_url('checkout'); ?>"><i class="icon-ok-sign icon-white"></i> <?php echo lang('button_checkout');?></a></div>
    </div>
</form>
<?php 
    else : 
?>

<p><?php echo lang('shopping_cart_empty'); ?></p>

<div class="submitFormButtons" style="text-align: right;">
	<a href="<?php echo base_url(); ?>" class="btn btn-small btn-info"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></a>
</div>

<?php 
    endif; 
?>