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

<h1><?php echo lang('guestbook_heading'); ?></h1>

<?php
    echo toc_validation_errors('guestbook');
?>

<div class="module-box">
    <h6 class="title"><?php echo lang('guestbook_new_heading'); ?></h6>
    
	<form id="guestbooks_edit" name="guestbooks_edit" action="<?php echo site_url('info/guestbooks/save');?>" method="post">
		<div class="row-fluid">
			<div class="span6">
                <div class="control-group">
                    <label class="control-label" for="title"><?php echo lang('field_title'); ?><em>*</em></label>
                    <div class="controls">
                    	<input type="text" id="title" name="title" value="<?php echo set_value('title'); ?>" />
                    </div>
                </div>
			</div>
			<div class="span6">
                <div class="control-group">
                    <label class="control-label" for="email"><?php echo lang('field_email'); ?><em>*</em></label>
                    <div class="controls">
                    	<input type="text" id="email" name="email" value="<?php echo set_value('field_email'); ?>" />
                    </div>
                </div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span6">
                <div class="control-group">
                    <label class="control-label" for="url"><?php echo lang('field_url'); ?><em>*</em></label>
                    <div class="controls">
                    	<input type="text" id="url" name="url" value="<?php echo set_value('url'); ?>" />
                    </div>
                </div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span6">
                <div class="control-group">
                    <label class="control-label" for="content"><?php echo lang('field_content'); ?><em>*</em></label>
                    <div class="controls">
                    	<textarea rows="5" cols="29" id="content" name="content"><?php echo set_value('content'); ?></textarea>
                    </div>
                </div>
			</div>
		</div>
    </form>
</div>
<div class="control-group clearfix">
    <button class="btn btn-small btn-info pull-right"><i class="icon-chevron-right icon-white"></i> <?php echo lang('button_continue'); ?></button>
    <a class="btn btn-small btn-info" href="<?php echo site_url('info/guestbooks'); ?>"><i class="icon-chevron-left icon-white"></i> <?php echo lang('button_back'); ?></a>
</div>