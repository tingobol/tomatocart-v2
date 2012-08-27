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


<h1><?php echo lang('orders_heading'); ?></h1>

<div class="moduleBox">

  <h6><span style="float: right;"><?php echo lang('order_total_heading') . ' ' . $info['total']; ?></span><?php echo  lang('order_date_heading') . ' ' . get_date_short($date_purchased) . ' <small>(' . $orders_status_name . ')</small>'; ?></h6>

  <div class="content">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="50%" valign="top">
          <h6><?php echo lang('order_billing_address_title'); ?></h6>

          <p><?php echo address_format($billing, '<br />'); ?></p>

          <h6><?php echo lang('order_payment_method_title'); ?></h6>

          <p><?php echo $payment_method; ?></p>
        </td>
        <td valign="top">
<?php
  if ($delivery != FALSE):
?>

          <h6><?php echo lang('order_delivery_address_title'); ?></h6>

          <p><?php echo address_format($delivery, '<br />'); ?></p>

<?php
    if (!empty($info['shipping_method'])) :
?>

          <h6><?php echo lang('order_shipping_method_title'); ?></h6>

          <p><?php echo $info['shipping_method']; ?></p>

<?php
    endif;

    if (!empty($info['tracking_no'])) :
?>    
          <h6><?php echo lang('order_shipping_tracking_no_title'); ?></h6>

          <p><?php echo $info['tracking_no']; ?></p>
<?php
    endif;
  endif;
?>
        </td>
      </tr>
    </table>
  </div>
</div>

<div class="moduleBox">

  <h6><?php echo lang('order_products_title'); ?></h6>
  
  <div class="content">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php
  if (isset($info['tax_groups']) && sizeof($info['tax_groups']) > 1) :
?>

      <tr>
        <td colspan="2"><h6><?php echo lang('order_products_title'); ?></h6></td>
        <td align="right"><h6><?php echo lang('order_tax_title'); ?></h6></td>
        <td align="right"><h6><?php echo lang('order_total_title'); ?></h6></td>
      </tr>

<?php
  else :
?>

      <tr>
        <td colspan="3"></td>
      </tr>

<?php
  endif;

  if (isset($products)) :
        foreach ($products as $product) :
            echo '      <tr>' . "\n" .
                 '        <td align="right" valign="top" width="30">' . $product['qty'] . '&nbsp;x</td>' . "\n" .
                 '        <td valign="top">' . $product['name'];
            
            if (isset($product['variants']) && (sizeof($product['variants']) > 0)) :
              foreach ($product['variants'] as $variant) :
                echo '<br /><nobr><small>&nbsp;<i> - ' . $variant['groups_name'] . ': ' . $variant['values_name'] . '</i></small></nobr>';
              endforeach;
            endif;
            
            echo '        </td>' . "\n";
            
            if (isset($info['tax_groups']) && sizeof($info['tax_groups']) > 1) :
              echo '      <td valign="top" align="right">' . osC_Tax::displayTaxRateValue($product['tax']) . '</td>' . "\n";
            endif;
            
            echo '        <td align="right" valign="top">' . currencies_display_price_with_tax_rate($product['final_price'], $product['tax'], $product['qty'], $info['currency'], $info['currency_value']) . '</td>' . "\n" .
                 '       </tr>' . "\n";
        endforeach;
    endif;
?>

    </table>

    <p>&nbsp;</p>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php
  foreach ($totals as $total) :
    echo '        <tr>' . "\n" .
         '         <td align="right">' . $total['title'] . '</td>' . "\n" .
         '         <td align="right" width="100">' . $total['text'] . '</td>' . "\n" .
         '       </tr>' . "\n";
  endforeach;
?>

    </table>
  </div>
</div>

<?php
 if ( !empty($order->info['wrapping_message']) ) :
?>

<div class="moduleBox">
  <h6><?php echo lang('gift_wrapping_message_heading'); ?></h6>

  <div class="content">
    <?php echo $order->info['wrapping_message']; ?>
  </div>
</div>

<?php
 endif;
?>

<?php
  if (isset($status_history)) :
?>

<div class="moduleBox">
  <h6><?php echo lang('order_history_heading'); ?></h6>

  <div class="content">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php
    foreach($status_history as $status):
      echo '    <tr>' . "\n" .
           '      <td valign="top" width="70">' . get_date_short($status['date_added']) . '</td>' . "\n" .
           '      <td valign="top" width="200">' . $status['orders_status_name'] . '</td>' . "\n" .
           '      <td valign="top">' . (!empty($status['comments']) ? nl2br($status['comments']) : '&nbsp;') . '</td>' . "\n" .
           '    </tr>' . "\n";
    endforeach;
?>

    </table>
  </div>
</div>

<?php
  endif;
?>

<div class="submitFormButtons">
	<a href="<?php echo site_url('account/orders'); ?>" class="button"><?php echo lang('button_back'); ?></a>
</div>