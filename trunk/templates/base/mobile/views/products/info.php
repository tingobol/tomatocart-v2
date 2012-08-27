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

<script type="text/javascript">
    $('#content').live( 'pageinit',function() {
        $('#product_slides').slides({
            preload: true,
            generatePagination: false,
            play: 3000,
            pause: 2000,
            hoverPause: true,
            start: 1
        });
    });
</script>

<h1><?php echo $name; ?></h1>

<!--Product Images-->
<div style="display:block; height: 400px; width:320px;margin: 0 auto;">
	<div id="product_slides">
		<div class="slides_container">
	    <?php
            if(count($images) > 0):
                foreach($images as $image):
        ?>
			<div class="slide"><img src="<?php echo product_image_url($image['image'], 'large'); ?>" /></div>
        <?php
                endforeach;
            endif;
        ?>
        </div>
    </div>
</div>
<!--END: Product Images-->

<div class="content">
    <!--Add to Cart Form-->
    <form id="cart_quantity" name="cart_quantity" action="<?php echo site_url('cart_add/' . $products_id); ?>" method="post" data-ajax="false">
        <ul>
            <li class="price"><?php echo $price; ?>&nbsp;<?php echo ( (config('DISPLAY_PRICE_WITH_TAX') == '1') ? lang('including_tax') : '' ); ?></li>
            <li>
				<span class="label"><?php echo lang('field_sku'); ?></span><?php echo $sku; ?>
            </li>
            <li>
              	<span class="label"><?php echo lang('field_availability'); ?></span><?php echo ($quantity > 0) ? lang('in_stock') : lang('out_of_stock'); ?>
            </li>
        <?php 
            if (config('PRODUCT_INFO_QUANTITY') == '1') :
        ?>
            <li>
              	<span class="label"><?php echo lang('field_quantity'); ?></span><?php echo $quantity . '&nbsp;' . $unit_class; ?>
            </li>
        <?php 
            endif; 
        ?>
        <?php 
            if (config('PRODUCT_INFO_MOQ') == '1') :
        ?>
            <li>
              <span class="label"><?php echo lang('field_moq'); ?></span><?php echo $moq . '&nbsp;' . $unit_class; ?>
            </li>
        <?php 
            endif; 
        ?>
        
        <?php 
            if (config('PRODUCT_INFO_ORDER_INCREMENT') == '1') :
        ?>
            <li>
              <span class="label"><?php echo lang('field_order_increment'); ?></span><?php echo $orderincrement . '&nbsp;' . $unit_class; ?>
            </li>
        <?php 
            endif; 
        ?>
            
        <?php
            if (isset($attributes) && is_array($attributes)):
                foreach($attributes as $attribute):
        ?>
            <li>          
              	<span class="label" valign="top"><?php echo $attribute['name']; ?>:</span><?php echo $attribute['value']; ?>
            </li>
        <?php
            endforeach;
        endif;
        ?>  
            
        <?php 
            if ($rating > 0):
        ?>  
            <li>      
              <span class="label"><?php echo lang('average_rating'); ?></span>
            <?php echo image_url('stars_' . $rating . '.png', sprintf(lang('rating_of_5_stars'), $rating)); ?>
            </li>
        <?php 
            endif; 
        ?>
            
        <!--Variants-->
        <?php
            if (isset($variants) && is_array($variants)):
                foreach($variants as $variant):
        ?>
            <li>          
              <span class="label" valign="top"><?php echo $variant['name']; ?>:</span><?php echo $variant['value']; ?>
            </li>
        <?php
                endforeach;
            endif;
        ?>  
        <!--Variants-->
            
            <li>
                <b><?php echo lang('field_short_quantity'); ?></b>&nbsp;<input type="text" size="3" value="<?php echo $moq; ?>" id="quantity" name="quantity">
            	<button title="<?php echo lang('button_add_to_cart'); ?>" alt="<?php echo lang('button_add_to_cart'); ?>" data-theme="b"><?php echo lang('button_add_to_cart'); ?></button>
            	<a data-role="button" href="<?php echo site_url('wishlist_add/' . $products_id); ?>"><?php echo lang('add_to_wishlist'); ?></a>
            </li>
            <li>
             	<p><?php echo $short_description; ?></p>
            </li>
        </ul>
    </form>
</div>

<div class="content">
    <h3><?php echo lang('section_heading_products_description'); ?></h3>
    
    <?php echo $description; ?>
</div>

<div class="content">
	<h3><?php echo lang('section_heading_reviews') . ' (' . $review_count . ')'; ?></h3>
  	<?php 
  	  if ($review_count > 0):
  	?>
		<ul data-role="listview" class="reviews">
      	<?php
      	    foreach ($reviews as $review) :
      	      $ratings = $review['ratings'];
      	?>
  			<li>
          		<div class="title">
            		<img alt="<?php echo sprintf(lang('rating_of_5_stars'), $review['reviews_rating']); ?>" src="<?php echo image_url('stars_' . $review['reviews_rating'] . '.png' ); ?>" />
            		<?php echo sprintf(lang('reviewed_by'), '&nbsp;' . $review['customers_name']); ?>&nbsp;&nbsp;(<?php echo lang('field_posted_on').'&nbsp;' . $review['date_added']; ?>)
          		</div>
          		<div class="text">
          		  <?php echo $review['reviews_text']; ?>
          		</div>
			<?php 
                if (count($ratings) > 0) :
    	    ?>
	    		<div class="ratings">
        	    <?php 
        	        foreach ($ratings as $rating) {
        	    ?>
					<p><img src="<?php echo image_url('stars_' . $rating['value'] . '.png'); ?>" alt="<?php echo sprintf(lang('rating_of_5_stars'), $rating['value']); ?>" /><span><?php echo $rating['name']; ?></span></p>
				<?php 
        	        }
        	    ?>
	    		</div>
	        <?php 
                endif;
            ?>
    		</li>
      	<?php 
      	    endforeach;
      	?>
		</ul>
  	<?php 
  	    else:
  	?>
		<p><?php echo lang('no_review'); ?></p>
  	<?php 
  	    endif;
  	?>

	<h3><?php echo lang('heading_write_review'); ?></h3>

<?php 
    if ( $is_logged_on ): 
?>
  	<p><?php echo lang('login_to_write_review'); ?></p>
<?php 
    else: 
?>
	<p><?php echo lang('introduction_rating'); ?></p>
  
  
<?php
    if (count($ratings) === 0) :
?>
    <p>
		<b><?php echo lang('review_lowest_rating_title'); ?></b>
				
        <div data-role="fieldcontain">
            <fieldset data-role="controlgroup" data-type="horizontal">
                <input type="radio" name="radio-choice-1" id="radio-choice-1" value="choice-1" checked="checked" />
                <label for="radio-choice-1">1</label>
                
                <input type="radio" name="radio-choice-1" id="radio-choice-2" value="choice-2"  />
                <label for="radio-choice-2">2</label>
                
                <input type="radio" name="radio-choice-1" id="radio-choice-3" value="choice-3"  />
                <label for="radio-choice-3">3</label>
                
                <input type="radio" name="radio-choice-1" id="radio-choice-4" value="choice-4"  />
                <label for="radio-choice-4">4</label>
                
                <input type="radio" name="radio-choice-1" id="radio-choice-5" value="choice-5"  />
                <label for="radio-choice-5">5</label>
            </fieldset>
        </div>
				
      	<b><?php echo lang('review_highest_rating_title'); ?></b>
    </p>
    <input type="hidden" id="rat_flag" name="rat_flag" value="0" />
<?php 
    else :
        foreach ($ratings as $rating) :
?>
    <p>
        <div data-role="fieldcontain">
            <fieldset data-role="controlgroup" data-type="horizontal">
                <legend><?php echo $rating['name']; ?></legend>
                
                <input type="radio" name="radio-choice-1" id="radio-choice-1" value="choice-1" checked="checked" />
                <label for="radio-choice-1">1</label>
                
                <input type="radio" name="radio-choice-1" id="radio-choice-2" value="choice-2"  />
                <label for="radio-choice-2">2</label>
                
                <input type="radio" name="radio-choice-1" id="radio-choice-3" value="choice-3"  />
                <label for="radio-choice-3">3</label>
                
                <input type="radio" name="radio-choice-1" id="radio-choice-4" value="choice-4"  />
                <label for="radio-choice-4">4</label>
                
                <input type="radio" name="radio-choice-1" id="radio-choice-5" value="choice-5"  />
				<label for="radio-choice-5">5</label>
            </fieldset>
        </div>
    </p>
<?php 
        endforeach;
    endif;
?>
  
  <h4><?php echo lang('field_review'); ?></h4>
  
  <div data-role="fieldcontain">
  	<textarea name="textarea" id="textarea"></textarea>
  </div>
  
  <button title="<?php echo lang('submit_reviews'); ?>" alt="<?php echo lang('submit_reviews'); ?>" data-theme="b"><?php echo lang('submit_reviews'); ?></button>
<?php 
    endif; 
?>
</div>