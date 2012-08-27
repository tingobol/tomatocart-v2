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

<div class="moduleBox">
    <div class="content">
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td width="50%" valign="top">
                    <p><?= '<b>' . lang('order_delivery_address_title') . '</b> '; ?></p>
                    
                <?php
                    if ($has_shipping_address) :
                ?>
                    <p><?= address_format($shipping_address, '<br />'); ?></p>
                    
                    <p><?= '<b>' . lang('order_shipping_method_title') . '</b> '; ?></p>
                    
                <?php
                    if ($has_shipping_method) :
                ?>
                    <p><?php echo $shipping_method; ?></p>
                <?php
                    endif;
                  endif;
                ?>
                </td>
                <td valign="top">
                    <p><b><?php echo lang('order_billing_address_title'); ?></b></p>
                    <p><?php echo address_format($billing_address, '<br />'); ?></p>
                    
                    <p><b><?php echo lang('order_payment_method_title'); ?></b></p>
                    <p><?php echo $billing_method; ?></p>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="2" valign="top">
                  	<div style="border: 1px; border-style: solid; border-color: #CCCCCC; background-color: #FBFBFB; padding: 5px;">
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                        
                        <?php
                          if ($number_of_tax_groups > 1) :
                        ?>
                        
                            <tr>
                                <td colspan="2"><b><?= lang('order_products_title'); ?></b></td>
                                <td align="right"><b><?= lang('order_tax_title'); ?></b></td>
                                <td align="right"><b><?= lang('order_total_title'); ?></b></td>
                            </tr>
                        
                        <?php
                          else:
                        ?>
                        
                          	<tr>
                            	<td colspan="3"><?= '<b>' . lang('order_products_title') . '</b> '; ?></td>
                          	</tr>

                        <?php
                            endif;
                            
                            foreach ($products as $product) :
                        ?>
							<tr>
								<td align="right" valign="top" width="30"><?php echo $product['quantity']; ?></td>
                                <td valign="top"><?php echo $product['name']; ?>
						    <?php 
                                //if ( (STOCK_CHECK == '1') && !$osC_ShoppingCart->isInStock($product['id']) ) {
                                  //echo '<span class="markProductOutOfStock">' . config('STOCK_MARK_PRODUCT_OUT_OF_STOCK') . '</span>';
                                //}
                                
                                if ( (isset($product['variants'])) && (sizeof($product['variants']) > 0) ) :
                                    foreach ($product['variants'] as $variants) :
                                        echo '<br /><nobr><small>&nbsp;<i> - ' . $variants['groups_name'] . ': ' . $variants['values_name'] . '</i></small></nobr>';
                                    endforeach;
                                endif;
                            ?>
                            	</td>
                            <?php     
                                if ($number_of_tax_groups > 1) {
                                  //echo '                <td valign="top" align="right">' . osC_Tax::displayTaxRateValue(get_tax_rate($product['tax_class_id'], $osC_ShoppingCart->getTaxingAddress('country_id'), $osC_ShoppingCart->getTaxingAddress('zone_id'))) . '</td>' . "\n";
                                }
                            ?>
                            	<td align="right" valign="top"><?php echo currencies_display_price($product['final_price'], $product['tax_class_id'], $product['quantity']); ?></td>
                            </tr>
                            <?php     
                                endforeach;
                            ?>
                        </table>
                        
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <?php
                            foreach ($order_totals as $module) :
                        ?>
							<tr>
                            	<td align="right"><?php echo $module['title']; ?></td>
                                <td align="right"><?php echo $module['text']; ?></td>
                            </tr>
                        <?php
                            endforeach;
                        ?>
                        </table>
                  	</div>
                </td>      
            </tr>
        </table>
    </div>
</div>

<?php
  if ($comments !== FALSE) :
?>

<div class="moduleBox">
    <h6><?php echo '<b>' . lang('order_comments_title') . '</b> '; ?></h6>
    
    <div class="content">
        <?php echo nl2br($comments) . osc_draw_hidden_field('comments', $comments); ?>
    </div>
</div>
<?php
  endif;
?>

<div class="submitFormButtons">

<?php
  echo '<form name="checkout_confirmation" action="' . $form_action_url . '" method="post" data-ajax="false">';

  if ($has_active_payment) {
    if ($confirmation) {
?>

<div class="moduleBox">
  <h6><?= lang('order_payment_information_title'); ?></h6>

  <div class="content">
    <p><?php echo $confirmation['title']; ?></p>

<?php
      if (isset($confirmation['fields'])) {
?>

    <table border="0" cellspacing="3" cellpadding="2">

<?php
        for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
?>

      <tr>
        <td width="10">&nbsp;</td>
        <td><?php echo $confirmation['fields'][$i]['title']; ?></td>
        <td width="10">&nbsp;</td>
        <td><?php echo $confirmation['fields'][$i]['field']; ?></td>
      </tr>

<?php
        }
?>

    </table>

<?php
      }

      if (isset($confirmation['text'])) {
?>

    <p><?php echo $confirmation['text']; ?></p>

<?php
      }
?>

  </div>
</div>

<?php
    }
  }
  
  if ($has_active_payment) {
    echo $process_button;
  }
  
?>
	<div style="text-align:right;"><button type="submit" class="small button" id="btn-confirm-order" data-role="button" data-theme="b"><?= lang('button_continue'); ?></button></div></form>
</div>