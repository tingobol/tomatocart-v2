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

<?php
    if (isset($orders) && is_array($orders) && (count($orders) > 0) ) {
        foreach ($orders as $order)
        {
            if (!empty($order['delivery_name'])) {
                $order_type = lang('order_shipped_to');
                $order_name = $order['delivery_name'];
            } else {
                $order_type = lang('order_billed_to');
                $order_name = $order['billing_name'];
            }
?>

<div class="moduleBox">

    <h6><span style="float: right;"><?php echo lang('order_status') . ' ' . $order['orders_status_name']; ?></span><?php echo lang('order_number') . ' ' . $order['orders_id']; ?></h6>
    
    <div class="content">
        <table border="0" width="100%" cellspacing="2" cellpadding="4">
            <tr>
                <td valign="top"><?php echo '<b>' . lang('order_date') . '</b> ' . $order['date_purchased'] . '<br /><b>' . $order_type . '</b> ' . $order_name; ?></td>
                <td width="150" valign="top"><?php echo '<b>' . lang('order_products') . '</b> ' . $order['number_of_products'] . '<br /><b>' . lang('order_cost') . '</b> ' . strip_tags($order['order_total']); ?></td>
                <td width="100" align="center">
                    <div style = "padding: 2px;">
                    	<a href="<?php echo site_url('account/orders/view/' . $order['orders_id']); ?>" class="button"><?php echo lang('button_view'); ?></a>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php
        }
    } 
    else 
    {
?>

<div class="moduleBox">
  <div class="content">
    <?php echo lang('no_orders_made_yet'); ?>
  </div>
</div>

<?php
    }
?>

<div class="submitFormButtons">
	<a href="<?php echo site_url('account'); ?>" class="button"><?php echo lang('button_back'); ?></a>
</div>