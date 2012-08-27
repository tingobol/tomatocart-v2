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

<h3><?= lang('login_new_customer_heading'); ?></h3>

<p><?= lang('login_new_customer_text'); ?></p>

<p align="right">
  <a class="button" href="<?= site_url('account/create'); ?>"><?= lang('button_continue'); ?></a>
</p>

<h3><?= lang('login_returning_customer_heading'); ?></h3>

<div class="contents">
  <form id="login" name="login" action="<?= site_url('account/login/process');?>" method="post">

    <p><?= lang('login_returning_customer_text'); ?></p>
    
    <ul>
      <li>
        <label for="email_address"><?= lang('field_customer_email_address');?><span class="required">*</span></label>
        <input type="text" id="email_address" name="email_address" value="<?= set_value('email_address');?>" />
      </li>
      <li>
        <label for="password"><?= lang('field_customer_password');?><em>*</em></label>
        <input type="password" id="password" name="password" value="<?= set_value('password');?>" />
      </li>
    </ul>
    
    <p>
      <?= sprintf(lang('login_returning_customer_password_forgotten'), site_url('account/password_forgotten'));?>
    </p>
    
    <p align="right">
      <button type="submit" class="button"><?= lang('button_sign_in'); ?></button>
    </p>
  </form>
</div>