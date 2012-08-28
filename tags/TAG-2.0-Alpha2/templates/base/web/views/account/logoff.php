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
    <h1><?php echo lang('sign_out_heading'); ?></h1>
    
    <div class="content clearfix">
        <div style="float: left;">
            <img src="<?php echo image_url('account_successs.png'); ?>" />
        </div>
        
        <div style="padding-top: 30px;">
          <p><?php echo lang('sign_out_text'); ?></p>
        </div>
    </div>
</div>

<div class="submitFormButtons clearfix">
    <a class="button fr" href="<?php echo base_url(); ?>"><?php echo lang('button_continue'); ?></a>
</div>