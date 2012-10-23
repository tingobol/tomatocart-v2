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
    if (isset($orders) && is_array($orders) && (count($orders) > 0) ) :
        foreach ($orders as $order):
            if (!empty($order['delivery_name'])) :
                $order_type = lang('order_shipped_to');
                $order_name = $order['delivery_name'];
            else :
                $order_type = lang('order_billed_to');
                $order_name = $order['billing_name'];
            endif;
?>
<h6><span class="pull-right"><?php echo lang('order_status') . ' ' . $order['orders_status_name']; ?></span><?php echo lang('order_number') . ' ' . $order['orders_id']; ?></h6>

<div class="module-box row-fluid">
	<div class="span4">
		<?php echo '<b>' . lang('order_date') . '</b> ' . $order['date_purchased'] . '<br /><b>' . $order_type . '</b> ' . $order_name; ?>
	</div>
	<div class="span4">
		<?php echo '<b>' . lang('order_products') . '</b> ' . $order['number_of_products'] . '<br /><b>' . lang('order_cost') . '</b> ' . strip_tags($order['order_total']); ?>
	</div>
	<div class="span4">
		<a class="btn btn-small btn-mini btn-info pull-right" href="<?php echo site_url('account/orders/view/' . $order['orders_id']); ?>" class="button"><i class="icon-list-alt icon-white"></i> <?php echo lang('button_view'); ?></a>
	</div>
</div>
<?php
        endforeach;
    else :
?>
    <p><?php echo lang('no_orders_made_yet'); ?></p>
<?php
    endif;
?>

<div class="control-group clearfix">
	<a href="<?php echo site_url('account'); ?>" class="btn btn-small btn-info pull-left"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_back'); ?></a>
</div>