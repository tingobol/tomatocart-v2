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

$step = 1;
?>

<h1><?php echo lang('checkout')?></h1>

<ul id="checkoutForm"> 
  <li id="checkoutMethodForm">
    <h3 class="formHeader">
       <?php echo $step++ . '<a onclick="javascript:void(0);">' . lang('checkout_method') . '</a>';?>
    </h3>
    <div class="formBody"></div>
  </li>
  
  <li id="billingInformationForm">
    <h3 class="formHeader">
       <?php echo $step++ . '<a onclick="javascript:void(0);">' . lang('checkout_billing_information') . '</a>'; ?>
    </h3>
    <div class="formBody"></div>
  </li>  
  
  <li id="shippingInformationForm">
    <h3 class="formHeader">
       <?php echo $step++ . '<a onclick="javascript:void(0);">' . lang('checkout_shipping_information') . '</a>';?>
    </h3>
    <div class="formBody"></div>
  </li>
  
  <li id="shippingMethodForm">
    <h3 class="formHeader">
       <?php echo $step++ . '<a onclick="javascript:void(0);">' . lang('checkout_shipping_method') . '</a>'; ?>
    </h3>
    <div class="formBody"></div>
  </li>
  
  <li id="paymentInformationForm">
    <h3 class="formHeader">
       <?php echo $step++ . '<a onclick="javascript:void(0);">' . lang('checkout_payment_information') . '</a>'; ?>
    </h3>
    <div class="formBody"></div>
  </li>
  
  <li id="orderConfirmationForm">
    <h3 class="formHeader">
       <?php echo $step++ . '<a onclick="javascript:void(0);">' . lang('checkout_order_review') . '</a>'; ?>
    </h3>
    <div class="formBody"></div>
  </li>
</ul>

<script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/checkout.js"></script>
<script>
var checkout = new jQuery.Toc.Checkout({
  logged_on: <?php echo ($logged_on == true)? 'true' : 'false';?>
});
</script>

<style>
#checkoutForm li > label:first-child {display: inline-block; width: 130px;}
#checkoutForm .formBody {display: none;}
#checkoutForm li {display: block; padding: 1px;}
</style>