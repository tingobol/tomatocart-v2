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

<div class="accordion" id="checkoutForm"> 
  <div class="accordion-group" id="checkoutMethodForm">
    <div class="accordion-heading">
       <?php echo '<a onclick="javascript:void(0);">' . $step++ . ' ' . $step++ . ' ' . lang('checkout_method') . '</a>';?>
    </div>
    <div class="accordion-body collapse"></div>
  </div>
  
  <div class="accordion-group" id="billingInformationForm">
    <div class="accordion-heading">
       <?php echo '<a onclick="javascript:void(0);">' . $step++ . ' ' . lang('checkout_billing_information') . '</a>'; ?>
    </div>
    <div class="accordion-body collapse"></div>
  </div>  
  
  <div class="accordion-group" id="shippingInformationForm">
    <div class="accordion-heading">
       <?php echo '<a onclick="javascript:void(0);">' . $step++ . ' ' . lang('checkout_shipping_information') . '</a>';?>
    </div>
    <div class="accordion-body collapse"></div>
  </div>
  
  <div class="accordion-group" id="shippingMethodForm">
    <div class="accordion-heading">
       <?php echo '<a onclick="javascript:void(0);">' . $step++ . ' ' . lang('checkout_shipping_method') . '</a>'; ?>
    </div>
    <div class="accordion-body collapse"></div>
  </div>
  
  <div class="accordion-group" id="paymentInformationForm">
    <div class="accordion-heading">
       <?php echo '<a onclick="javascript:void(0);">' . $step++ . ' ' . lang('checkout_payment_information') . '</a>'; ?>
    </div>
    <div class="accordion-body collapse"></div>
  </div>
  
  <div class="accordion-group" id="orderConfirmationForm">
    <div class="accordion-heading">
       <?php echo '<a onclick="javascript:void(0);">' . $step++ . ' ' . lang('checkout_order_review') . '</a>'; ?>
    </div>
    <div class="accordion-body collapse"></div>
  </div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/checkout.js"></script>
<script>
<!--
var checkout = new jQuery.Toc.Checkout({
  logged_on: <?php echo ($logged_on == true)? 'true' : 'false';?>
});
//-->
</script>