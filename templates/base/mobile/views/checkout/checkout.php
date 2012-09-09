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
<div class="checkout-form" data-role="content">
  <h1><?php echo lang('checkout')?></h1>
  
  <ul id="checkoutForm" data-role="collapsible-set"> 
    <li id="checkoutMethodForm" data-role="collapsible" data-content-theme="c">
      <h3 class="formHeader">
         <?php echo '<a onclick="javascript:void(0);">' .  $step++ . '.&nbsp;' . lang('checkout_method') . '</a>';?>
      </h3>
      <div class="formBody"></div>
    </li>
    
    <li id="billingInformationForm" data-role="collapsible" data-content-theme="c">
      <h3 class="formHeader">
         <?php echo '<a onclick="javascript:void(0);">' .  $step++ . '.&nbsp;' . lang('checkout_billing_information') . '</a>'; ?>
      </h3>
      <div class="formBody"></div>
    </li>  
    
    <li id="shippingInformationForm" data-role="collapsible" data-content-theme="c">
      <h3 class="formHeader">
         <?php echo '<a onclick="javascript:void(0);">' .  $step++ . '.&nbsp;' . lang('checkout_shipping_information') . '</a>';?>
      </h3>
      <div class="formBody"></div>
    </li>
    
    <li id="shippingMethodForm" data-role="collapsible" data-content-theme="c">
      <h3 class="formHeader">
         <?php echo '<a onclick="javascript:void(0);">' .  $step++ . '.&nbsp;' . lang('checkout_shipping_method') . '</a>'; ?>
      </h3>
      <div class="formBody"></div>
    </li>
    
    <li id="paymentInformationForm" data-role="collapsible" data-content-theme="c">
      <h3 class="formHeader">
         <?php echo '<a onclick="javascript:void(0);">' .  $step++ . '.&nbsp;' . lang('checkout_payment_information') . '</a>'; ?>
      </h3>
      <div class="formBody"></div>
    </li>
    
    <li id="orderConfirmationForm" data-role="collapsible" data-content-theme="c">
      <h3 class="formHeader">
         <?php echo '<a onclick="javascript:void(0);">' .  $step++ . '.&nbsp;' . lang('checkout_order_review') . '</a>'; ?>
      </h3>
      <div class="formBody"></div>
    </li>
  </ul>
</div>  
  
  <script type="text/javascript" src="<?php echo base_url();?>templates/base/mobile/javascript/toc.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>templates/base/mobile/javascript/checkout.js"></script>
  <script type="text/javascript">
  $(".type-index").live('pageinit', function() {
    var checkout = new jQuery.Toc.Checkout({
      logged_on: <?php echo ($logged_on == true)? 'true' : 'false';?>
    });
  });
  </script>
  <style>
  #checkoutForm li > label:first-child {display: inline-block; width: 130px;}
  #checkoutForm .formBody {display: none;}
  #checkoutForm li {display: block; padding: 1px;}
  </style>