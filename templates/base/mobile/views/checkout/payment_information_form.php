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

<?php
  if (config('DISPLAY_CONDITIONS_ON_CHECKOUT') == '1') :
?>
    <div class="moduleBox">
        <h6><?= lang('order_conditions_title'); ?></h6>
        
        <div class="content">
        <?= sprintf(lang('order_conditions_description'), site_url('articles/4')) . '<br /><br />' . form_checkbox('conditions', '1', $order_conditions);?>
        <label for="conditions"><?= lang('order_conditions_acknowledge');?></label>
        </div>
    </div>
    
    <div class="clear"></div>
<?php
  endif;
?>

<div class="moduleBox">

  <div class="content">

<?php
  if (sizeof($selection) > 0) {
?>

    <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
      <?= '<b>' . lang('please_select'); ?>
    </div>

    <p style="margin-top: 0px;"><?= lang('choose_payment_method'); ?></p>

<?php
  } else {
?>

    <p style="margin-top: 0px;"><?= lang('only_one_payment_method_available'); ?></p>

<?php
  }
?>
	<fieldset data-role="controlgroup">
    <table id="payment_methods" border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  $radio_buttons = 0;
  for ($i=0, $n=sizeof($selection); ($i<$n); $i++) {
?>

      <tr id="payment_method_<?php echo $selection[$i]['id']; ?>">
        <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php
    if ( ($n == 1) || ($has_billing_method && ($selection[$i]['id'] == $selected_billing_method_id)) ) {
      echo '          <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(\'checkout_payment\', this)">' . "\n";
    } else {
      echo '          <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(\'checkout_payment\', this)">' . "\n";
    }
?>

            <td width="10">&nbsp;</td>

<?php
    if ($n > 1) {
?>

            <td colspan="3"><?= '<b>' . $selection[$i]['module'] . '</b>'; ?></td>
            <td align="right"><?= form_radio('payment_method', $selection[$i]['id'], ($has_billing_method ? $selected_billing_method_id : null), 'id="' . $selection[$i]['id'] . '"'); ?></td>

<?php
    } else {
?>

            <td colspan="4"><?= '<b>' . $selection[$i]['module'] . '</b>' . osc_draw_hidden_field('payment_method', $selection[$i]['id']); ?></td>

<?php
  }
?>

            <td width="10">&nbsp;</td>
          </tr>

<?php
    if (isset($selection[$i]['error'])) {
?>

          <tr>
            <td width="10">&nbsp;</td>
            <td colspan="4"><?php echo $selection[$i]['error']; ?></td>
            <td width="10">&nbsp;</td>
          </tr>

<?php
    } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
?>

          <tr>
            <td width="10">&nbsp;</td>
            <td colspan="4"><table border="0" cellspacing="0" cellpadding="2">

<?php
      for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
?>

              <tr>
                <td width="10">&nbsp;</td>
                <td><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                <td width="10">&nbsp;</td>
                <td><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                <td width="10">&nbsp;</td>
              </tr>

<?php
      }
?>

            </table></td>
            <td width="10">&nbsp;</td>
          </tr>

<?php
    }
?>

        </table></td>
      </tr>

<?php
    $radio_buttons++;
  }
?>

    </table>
    </fieldset>
  </div>
</div>

<div class="moduleBox">
  <h6><?= lang('add_comment_to_order_title'); ?></h6>

  <div class="content">
    <?= form_textarea(array('id' => 'payment_comments', 'name' => 'payment_comments', 'value' => $payment_comments, 'style' => 'width: 98%;')); ?>
  </div>
</div>

<br />
    
<p align="right">
  <button type="submit" class="small button" id="btn-save-payment-form" data-theme="b"><?= lang('button_continue'); ?></button>
</p>