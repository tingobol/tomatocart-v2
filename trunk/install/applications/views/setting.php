<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

  $www_location = 'http://' . $_SERVER['HTTP_HOST'];

  if (isset($_SERVER['REQUEST_URI']) && (empty($_SERVER['REQUEST_URI']) === false)) {
    $www_location .= $_SERVER['REQUEST_URI'];
  } else {
    $www_location .= $_SERVER['SCRIPT_FILENAME'];
  }

  $www_location = substr($www_location, 0, strpos($www_location, 'install'));

  $dir_fs_www_root = realpath(dirname(__FILE__) . '/../../../') . '/';
?>
<div class="container clearfix">
    <div class="row-fluid">
    	<div class="span3">
            <ul class="nav nav-list">
                <li class="<?php echo ($step == 1) ? 'active' : ''; ?>"><a href="javascript:void(0);" title="<?php echo lang('nav_menu_step_1_text'); ?>"><i class="icon-chevron-right"></i> <?php echo lang('nav_menu_step_1_text'); ?></a></li>
                <li class="<?php echo ($step == 2) ? 'active' : ''; ?>"><a href="javascript:void(0);" title="<?php echo lang('nav_menu_step_2_text'); ?>"><i class="icon-chevron-right"></i> <?php echo lang('nav_menu_step_2_text'); ?></a></li>
                <li class="<?php echo ($step == 3) ? 'active' : ''; ?>"><a href="javascript:void(0);" title="<?php echo lang('nav_menu_step_3_text'); ?>"><i class="icon-chevron-right"></i> <?php echo lang('nav_menu_step_3_text'); ?></a></li>
                <li class="<?php echo ($step == 4) ? 'active' : ''; ?>"><a href="javascript:void(0);" title="<?php echo lang('nav_menu_step_4_text'); ?>"><i class="icon-chevron-right"></i> <?php echo lang('nav_menu_step_4_text'); ?></a></li>
                <li class="<?php echo ($step == 5) ? 'active' : ''; ?>"><a href="javascript:void(0);" title="<?php echo lang('nav_menu_step_5_text'); ?>"><i class="icon-chevron-right"></i> <?php echo lang('nav_menu_step_5_text'); ?></a></li>
                <li class="<?php echo ($step == 6) ? 'active' : ''; ?>"><a href="javascript:void(0);" title="<?php echo lang('nav_menu_step_6_text'); ?>"><i class="icon-chevron-right"></i> <?php echo lang('nav_menu_step_6_text'); ?></a></li>
			</ul>
      	  	<div id="mBox">
      	    	<div id="mBoxContents"></div>
  	  		</div>  
    
            <div id="mainBlock">
            </div>
    	</div>
    	<div class="span9 content">
          	<h1><?php echo lang('page_title_online_store_settings'); ?></h1>
          
          	<p><?php echo lang('text_online_store_settings'); ?></p>
          	
            <form name="install" id="installForm" action="<?php echo site_url('index/index/finish'); ?>" method="post" class="form-horizontal">
                <div class="info">
                    <div class="control-group">
                        <label class="control-label" for="HTTP_WWW_ADDRESS"><?php echo lang('param_web_address'); ?></label>
                        <div class="controls">
                        	<input type="text" id="HTTP_WWW_ADDRESS" name="HTTP_WWW_ADDRESS" value="<?php echo empty($HTTP_WWW_ADDRESS) ? $www_location : $HTTP_WWW_ADDRESS; ?>" />
                        </div>
                        <div class="description"><?php echo lang('param_web_address_description'); ?></div>
                    </div>                
                    <div class="control-group">
                        <label class="control-label" for="CFG_STORE_NAME"><?php echo lang('param_store_name'); ?>:</label>
                        <div class="controls">
                        	<input type="text" id="CFG_STORE_NAME" name="CFG_STORE_NAME" value="<?php echo empty($store_name) ? '' : $store_name; ?>" />
                        </div>
                        <div class="description"><?php echo lang('param_store_name_description'); ?></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="CFG_STORE_OWNER_NAME"><?php echo lang('param_store_owner_name'); ?>:</label>
                        <div class="controls">
                        	<input type="text" id="CFG_STORE_OWNER_NAME" name="CFG_STORE_OWNER_NAME" value="<?php echo empty($store_owner_name) ? '' : $store_owner_name; ?>" />
                        </div>
                        <div class="description"><?php echo lang('param_store_owner_name_description'); ?></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="CFG_STORE_OWNER_EMAIL_ADDRESS"><?php echo lang('param_store_owner_email_address'); ?>:</label>
                        <div class="controls">
                        	<input type="text" id="CFG_STORE_OWNER_EMAIL_ADDRESS" name="CFG_STORE_OWNER_EMAIL_ADDRESS" value="<?php echo empty($store_owner_email) ? '' : $store_owner_email; ?>" />
                        	<span class="help-inline"></span>
                        </div>
                        <div class="description"><?php echo lang('param_store_owner_email_address_description'); ?></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="CFG_ADMINISTRATOR_USERNAME"><?php echo lang('param_administrator_username'); ?>:</label>
                        <div class="controls">
                        	<input type="text" id="CFG_ADMINISTRATOR_USERNAME" name="CFG_ADMINISTRATOR_USERNAME" value="<?php echo empty($admin_user_name) ? '' : $admin_user_name; ?>" />
                            <span class="help-inline"></span>
                        </div>
                        <div class="description"><?php echo lang('param_administrator_username_description'); ?></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="CFG_ADMINISTRATOR_PASSWORD"><?php echo lang('param_administrator_password'); ?>:</label>
                        <div class="controls">
                        	<input type="password" id="CFG_ADMINISTRATOR_PASSWORD" name="CFG_ADMINISTRATOR_PASSWORD" value="<?php echo empty($admin_pwd) ? '' : $admin_pwd; ?>" />
                            <span class="help-inline"></span>
                        </div>
                        <div class="description"><?php echo lang('param_administrator_password_description'); ?></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="CFG_CONFIRM_PASSWORD"><?php echo lang('param_confirm_password'); ?>:</label>
                        <div class="controls">
                        	<input type="password" id="CFG_CONFIRM_PASSWORD" name="CFG_CONFIRM_PASSWORD" value="<?php echo empty($admin_pwd) ? '' : $admin_pwd; ?>" />
                            <span class="help-inline"></span>
                        </div>
                        <div class="description"><?php echo lang('param_administrator_password_description'); ?></div>
                    </div>
                	<div class="control-group clearfix" style="margin-top: 8px;">
                    	<label class="checkbox control-label pull-left" for="license"><?php echo lang('param_database_import_sample_data'); ?>&nbsp;&nbsp;<input type="checkbox" id="DB_INSERT_SAMPLE_DATA" name="DB_INSERT_SAMPLE_DATA" checked="checked" /></label>
                    	<div class="description"><?php echo lang('param_database_import_sample_data_description'); ?></div>
                    </div>
                </div>
            
                <div class="">
                    <div id="alert_error_panel" class="pull-left alert" style="margin-bottom: 5px;display: none;">
                    </div>
                    <div class="controls pull-right">
                    	<a href="<?php echo site_url(); ?>" class="btn btn-info"><i class="icon-remove icon-white"></i> &nbsp;<?php echo lang('image_button_cancel'); ?></a>
                    	<a id="btn_continue" class="btn btn-info"><i class="icon-ok icon-white"></i> &nbsp;<?php echo lang('image_button_continue'); ?></a>
                    </div>
                </div>
            </form>
    	</div>
	</div>
</div>

<script type="text/javascript">
(function($){
	$('#btn_continue').on('click',function(){
		var errors = $('#installForm').find('.error');
		if(errors.size() > 0){
			$('#alert_error_panel').show();
			$('#alert_error_panel').html("<?php echo lang('error_msg_form'); ?>");
		}else{
			$('#alert_error_panel').hide();
		}
		alert(errors.size());
	    //$('#installForm').submit();
	});

	function checkValue(obj,info,is_error){
		if(is_error){
			obj.parent().parent().addClass('error');
			obj.parent().find('.help-inline').html("<?php echo lang(info);?>");
		}else{
			obj.parent().parent().removeClass('error');
			obj.parent().find('.help-inline').html('');
		}
	}

	$('#CFG_STORE_OWNER_EMAIL_ADDRESS').on('blur',function(){
		var v = $(this).val();
		var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
		checkValue($(this),'error_email_format',v&&reg.test(v));
		/*
		if(v){
			if(!reg.test(v)){
				$(this).parent().parent().addClass('error');
				$(this).parent().find('.help-inline').html("<?php echo lang('error_email_format');?>");
			}else{
				$(this).parent().parent().removeClass('error');
				$(this).parent().find('.help-inline').html('');
			}
		}
		*/
	});

	$('#CFG_ADMINISTRATOR_USERNAME').on('blur',function(){
		var v = $(this).val();
		if(!v){
				$(this).parent().parent().addClass('error');
				$(this).parent().find('.help-inline').html("<?php echo lang('error_name_is_null');?>");			
		}else{
				$(this).parent().parent().removeClass('error');
				$(this).parent().find('.help-inline').html('');
		}
	});


	$('#CFG_ADMINISTRATOR_PASSWORD').on('blur',function(){
		var v = $(this).val();
		if(v && v.length < 4){
				$(this).parent().parent().addClass('error');
				$(this).parent().find('.help-inline').html("<?php echo lang('error_pwd_is_null');?>");			
		}else{
				$(this).parent().parent().removeClass('error');
				$(this).parent().find('.help-inline').html('');
		}		
	});

	$('#CFG_CONFIRM_PASSWORD').on('blur',function(){
		var v = $(this).val();
		var fpwd = $('#CFG_ADMINISTRATOR_PASSWORD').val();
		if(v != fpwd){
				$(this).parent().parent().addClass('error');
				$(this).parent().find('.help-inline').html("<?php echo lang('error_pwd_not_match');?>");			
		}else{
				$(this).parent().parent().removeClass('error');
				$(this).parent().find('.help-inline').html('');
		}		
	});
	
})($);
</script>
