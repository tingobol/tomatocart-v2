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

<h1><?php echo $name; ?></h1>

<div class="module-box">
    <div class="content row-fluid clearfix">
        
        <!--Product Images-->
        <div class="span6" id="product-images">
            <a id="default-image" href="<?php echo product_image_url($default_image, 'large'); ?>" rel="showTitle:false, smoothMove:15,zoomWidth:320, zoomHeight:240, adjustY:0, adjustX:10" class="cloud-zoom">
            	<img title="<?php echo $name; ?>" alt="<?php echo $name; ?>" src="<?php echo product_image_url($default_image, 'product_info'); ?>">
            </a>
        <?php 
            foreach ($images as $image): 
        ?>
          	<a rel="useZoom: 'default-image', smallImage: '<?php echo product_image_url($image['image'], 'product_info'); ?>' " class="cloud-zoom-gallery" href="<?php echo product_image_url($image['image'], 'large'); ?>">
				<img title="<?php echo $name; ?>" alt="<?php echo $name; ?>" src="<?php echo product_image_url($image['image'], 'mini'); ?>" />
          	</a>
	    <?php 
	        endforeach; 
	    ?>
        </div>
        <!--END: Product Images-->
        
        <!--Add to Cart Form-->
        <form class="form-inline span6" id="cart-quantity" name="cart-quantity" action="<?php echo site_url('cart_add/' . $products_id); ?>" method="post">
            <table id="product-info">
                <tr>
                  	<td colspan="2" class="price">
                  	    <?php echo currencies_format($price); ?>&nbsp;<?php echo ( (config('DISPLAY_PRICE_WITH_TAX') == '1') ? lang('including_tax') : '' ); ?>
                  	</td>
                </tr>
                <tr>
                  	<td class="label"><?php echo lang('field_sku'); ?></td>
                	<td><?php echo $sku; ?>&nbsp;</td>
                </tr>
                <tr>
                  	<td class="label"><?php echo lang('field_availability'); ?></td>
                	<td><?php echo ($quantity > 0) ? lang('in_stock') : lang('out_of_stock'); ?></td>
                </tr>
            <?php 
                if (config('PRODUCT_INFO_QUANTITY') == '1') :
            ?>
                <tr>
                  	<td class="label"><?php echo lang('field_quantity'); ?></td>
                	<td id="product-info-qty"><?php echo $quantity . '&nbsp;' . $unit_class; ?></td>
                </tr>
            <?php 
                endif; 
            ?>
            
            <?php 
                if (config('PRODUCT_INFO_MOQ') == '1') :
            ?>
                <tr>
                  	<td class="label"><?php echo lang('field_moq'); ?></td>
                	<td><?php echo $product['moq'] . '&nbsp;' . $unit_class; ?></td>
                </tr>
            <?php 
                endif; 
            ?>
            <?php 
                if (config('PRODUCT_INFO_ORDER_INCREMENT') == '1') :
            ?>
                <tr>
                  	<td class="label"><?php echo lang('field_order_increment'); ?></td>
                	<td><?php echo $product['orderincrement'] . '&nbsp;' . $unit_class; ?></td>
                </tr>
            <?php 
                endif; 
            ?>
                
            <?php 
                if (isset($url) && !empty($url)) :
            ?>
                <tr>
                  <td colspan="2"><?php echo sprintf(lang('go_to_external_products_webpage'), site_url('products/info/goto/' . urlencode($url))); ?></td>
                </tr>
            <?php 
                endif; 
            ?>
                
            <?php 
                if (isset($date_available) && !empty($date_available)) :
                    if (strtotime($date_available) > now()) :
            ?>
                <tr>
                  <td colspan="2"><?php echo sprintf(lang('date_availability'), get_date_long($date_available)); ?></td>
                </tr>
            <?php 
                    endif;
                endif; 
            ?>
                
            <?php
                if (isset($attributes) && is_array($attribute)):
                    foreach($attributes as $attribute):
            ?>
                <tr>          
                  	<td class="label"><?php echo $attribute['name']; ?>:</td>
                	<td><?php echo $attribute['value']; ?></td>
                </tr>
            <?php
                    endforeach;
                endif;
            ?>  
                
            <?php 
                if ($rating > 0):
            ?>  
                <tr>      
                  	<td class="label"><?php echo lang('average_rating'); ?></td>
                	<td><img src="<?php echo image_url('stars_' . $rating . '.png'); ?>" alt="<?php echo sprintf(lang('rating_of_5_stars'), $rating); ?>" title="<?php echo sprintf(lang('rating_of_5_stars'), $rating); ?>" /></td>
                </tr>
            <?php 
                endif; 
            ?>
                
            <!--Variants-->
            <?php
                if (isset($combobox_array) && is_array($combobox_array)):
                    foreach($combobox_array as $groups_name => $combobox):
            ?>
           		<tr class="variant-comb">
                    <td class="label"><?php echo $groups_name; ?> :</td>
                    <td><?php echo $combobox; ?></td>
                 </tr>
            <?php
                endforeach;
              endif;
            ?>  
            <!--Variants-->
                
                <tr>
                  	<td colspan="2">
                    	<b><?php echo lang('field_short_quantity'); ?></b>&nbsp;
                    	<input type="text" size="3" value="<?php echo $moq?>" id="quantity" name="quantity">&nbsp;
                    	<button class="btn btn-info" title="<?php echo lang('button_add_to_cart'); ?>"><i class="icon-shopping-cart icon-white "></i> <?php echo lang('button_add_to_cart'); ?></button>
                  	</td>
                </tr>
                <tr>
                  	<td colspan="2">
                    	<a href="javascript:void(0);"><?php echo lang('add_to_compare'); ?></a>
                		&nbsp;<span>|</span>&nbsp;
                		<a href="javascript:void(0);"><?php echo lang('add_to_wishlist'); ?></a>
                  	</td>
                </tr>
                <tr>
                  	<td colspan="2">
                    	<p><?php echo $short_description; ?></p>
                  	</td>
                </tr>
            </table>
        </form>
        
        <!--END: Add to Cart Form-->
    </div>
</div>

<div class="module-box clearfix">
	<ul id="product-info-tab" class="nav nav-tabs">
        <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo lang('section_heading_products_description'); ?></a></li>
        <li><a href="#tab-reviews" data-toggle="tab"><?php echo lang('section_heading_reviews') ?></a></li>
	</ul>

	<div id="product-info-tab-content" class="tab-content">
        <div class="tab-pane active" id="tab-description">
        	<?php echo $description; ?>
        </div>

        <div class="tab-pane" id="tab-reviews">
            <p><?php echo toc_validation_errors('reviews'); ?></p>
            
            <?php 
                if (isset($reviews) && (count($reviews) > 0)) :
                    foreach ($reviews as $review): 
            ?>
            <dl class="review">
    			<dt>
    				<img title="<?php echo sprintf(lang('rating_of_5_stars'), $review['reviews_rating']); ?>" alt="<?php echo sprintf(lang('rating_of_5_stars'), $review['reviews_rating']); ?>" src="<?php echo image_url('stars_' . $review['reviews_rating'] . '.png'); ?>">
                	&nbsp;&nbsp;&nbsp;&nbsp;<?php echo sprintf(lang('reviewed_by'), $review['customers_name']);?>&nbsp;&nbsp;<?php echo lang('field_posted_on');?>&nbsp;(<?php echo get_date_short($review['date_added']);?>)
              	</dt>
              	<dd>
              		<?php 
              		    if (isset($review['ratings']) && (count($review['ratings']) > 0)):
              		        $customers_ratings = $review['ratings'];
              		?>
              		<table class="customers-ratings">
              			<?php 
              			    foreach($customers_ratings as $rating):
              			?>
              			<tr>
              				<td class="name"><?php echo $rating['name']; ?></td>
              				<td><img src="<?php echo image_url('stars_' . $rating['value'] . '.png'); ?>" alt="<?php echo sprintf(lang('rating_of_5_stars'), $rating['value']); ?>" title="<?php echo sprintf(lang('rating_of_5_stars'), $rating['value']); ?>" /></td>
              			</tr>
              			<?php 
              			    endforeach;
              			?>
              		</table>
                    <?php 
                        endif;
                    ?>
                    <p><?php echo $review['reviews_text'];?></p>
            	</dd>              
            </dl>
            <?php 
                    endforeach;
                else :
                    echo '<p>' . lang('no_review') . '</p>';
                endif;
            ?>
        
            <hr />
            
            <h3><?php echo lang('heading_write_review'); ?></h3>
            
            <?php 
                if($is_logged_on === FALSE):
            ?>
                <p><?php echo sprintf(lang('login_to_write_review'), site_url('login')); ?></p>
            <?php 
                else:
            ?>        
            <p><?php echo lang('introduction_rating');?></p>
            
            <form method="post" action="<?php echo site_url('products/info/save_review/' . $products_id); ?>" name="newReview" id="frmReviews">
              	<?php
              	    if (isset($ratings) && (count($ratings) == 0)) :
              	?>
                <p>
                	<b><?php echo lang('field_review_rating'); ?></b>&nbsp;&nbsp;&nbsp;
                    
              	    <?php echo lang('review_lowest_rating_title');?> 
                    <input type="radio" value="1" id="rating1" name="rating" <?php echo set_radio('rating', '1'); ?> />&nbsp;&nbsp;
                    <input type="radio" value="2" id="rating2" name="rating" <?php echo set_radio('rating', '2'); ?> />&nbsp;&nbsp;
                    <input type="radio" value="3" id="rating3" name="rating" <?php echo set_radio('rating', '3', TRUE); ?> />&nbsp;&nbsp;
                    <input type="radio" value="4" id="rating4" name="rating" <?php echo set_radio('rating', '4'); ?> />&nbsp;&nbsp;
                    <input type="radio" value="5" id="rating5" name="rating" <?php echo set_radio('rating', '5'); ?> /> 
                    <?php echo lang('review_highest_rating_title');?>
                </p>
    				<input type="hidden" id="rat_flag" name="rat_flag" value="0" />
              	<?php 
              	    else :
              	?>
                    <table class="ratings" border="1" cellspacing="0" cellpadding="0">
                      <thead>
                        <tr>
                          <td width="45%">&nbsp;</td>
                          <td><?php echo lang('1_star'); ?></td>
                          <td><?php echo lang('2_stars'); ?></td>
                          <td><?php echo lang('3_stars'); ?></td>
                          <td><?php echo lang('4_stars'); ?></td>
                          <td><?php echo lang('5_stars'); ?></td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                            $i = 0;
                            foreach ( $ratings as $key => $value ):
                        ?>
                          <tr>
                            <td><?php echo $value;?></td>
                            <td><input type="radio" value="1" name="rating_<?php echo $key; ?>" title="<?php echo lang('1_star'); ?>" <?php echo set_radio('rating_' . $key, '1'); ?> /></td>
                            <td><input type="radio" value="2" name="rating_<?php echo $key; ?>" title="<?php echo lang('2_stars'); ?>" <?php echo set_radio('rating_' . $key, '2'); ?> /></td>
                            <td><input type="radio" value="3" name="rating_<?php echo $key; ?>" title="<?php echo lang('2_stars'); ?>" <?php echo set_radio('rating_' . $key, '3', TRUE); ?> /></td>
                            <td><input type="radio" value="4" name="rating_<?php echo $key; ?>" title="<?php echo lang('2_stars'); ?>" <?php echo set_radio('rating_' . $key, '4'); ?> /></td>
                            <td><input type="radio" value="5" name="rating_<?php echo $key; ?>" title="<?php echo lang('2_stars'); ?>" <?php echo set_radio('rating_' . $key, '5'); ?> /></td>
                          </tr>
                        <?php 
                                $i++;
                            endforeach;
                        ?>
    	              </tbody>
                    </table>
                    <input type="hidden" value="<?php echo $i; ?>" name="radio_count" id="radio_count">
                <?php 
              	    endif;
              	?>
              
              	<h6><?php echo lang('field_review');?></h6>
              
              	<textarea id="review" rows="5" cols="45" name="review"><?php echo set_value('review'); ?></textarea>            
                
                <div class="submitFormButtons">
                    <button type="submit" class="button small" name="<?php echo lang('submit_reviews');?>"><?php echo lang('submit_reviews');?></button>
                </div>
            </form>
            <?php 
                endif;
            ?>
        </div>
    </div>
</div> 

<script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/cloud-zoom/cloud-zoom.1.0.2.js"></script>
<script>
	$('#product-info-tab a').click(function (e) {
    	e.preventDefault();

    	$this = $(this);

    	$this.parent().find('a').removeClass('active');
    	$this.addClass('active');
    	
    	$('#product-info-tab-content .tab-pane').removeClass('active');
    	$($(this).attr('href')).addClass('active');
    })
</script>