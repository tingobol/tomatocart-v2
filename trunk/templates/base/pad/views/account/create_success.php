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

<div class="box">
    <h1 class="title"><?php echo lang('create_account_success_heading'); ?></h1>
    
    <div class="content">
        <div style="float: left;"><img src="<?php echo image_url('account_successs.png'); ?>" title="<?php echo $title; ?>" alt="<?php echo $title; ?>" /></div>
        
        <div style="padding-top: 30px;">
          	<p><?php echo sprintf(lang('success_account_created'), site_url('info/contact')); ?></p>
        </div>
        <div style="clear: both"></div>
    </div>
</div>

<div class="submitFormButtons" style="text-align: right;">
	<a class="button" data-role="button" href="<?php echo base_url(); ?>" data-ajax="false" data-theme="b"><?php echo lang('button_continue'); ?></a>
</div>