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
    		<h1><?php echo lang('text_finished_title'); ?></h1>
    		<div><?php echo lang('text_finished'); ?></div>
    		<p class="well">
    		<?php echo lang('text_remove_install_dir'); ?>
    		</p>
    		<form id="finish_form">
               	<label class="checkbox">
               	<input type="checkbox" id="DB_INSERT_SAMPLE_DATA" name="DB_INSERT_SAMPLE_DATA" checked="checked"/>
               	<?php echo lang('param_database_import_sample_data'); ?>&nbsp;&nbsp;-->&nbsp;&nbsp;<?php echo lang('param_database_import_sample_data_description'); ?>
               	</label>
    			<button type="button" id="btn_finish" class="btn btn-primary btn-large"><?php echo lang('image_install_finished'); ?></button>
    			<a id="btn_admin" href="/admin" style="display: none;" class="btn btn-primary btn-large"><?php echo lang('image_button_admin'); ?></a>
    		</form>
    		<div id="process_bar" class="progress progress-striped active" style="display: none;">
		    	<div class="bar" style="width: 100%;"></div>
    		</div>
    	</div>
	</div>
</div>

<script type="text/javascript">
	(function($){
		var v = $('#DB_INSERT_SAMPLE_DATA').attr('checked');
		
			/*
			var pct = 0;
			setInterval(function(){
				pct+=5;
				$('#process_bar').find('div').width(pct%100 + '%');
			},1000);
			*/
		$('#btn_finish').on('click',function(){
			var _btn = $(this);
			_btn.addClass('disabled');
			_btn.attr('disabled',true);
			var url = "<?php echo site_url('index/index/import_data')?>?st="+new Date().getTime();
			if(v){
				url = url+ '&DB_INSERT_SAMPLE_DATA=true';
			}
			$('#process_bar').show();
			$.get(url,null,function(data){
				if(data.import_dt){
				}
				$('#process_bar').hide();
				_btn.hide();
				_btn.removeClass('disabled');
				_btn.removeAttr('disabled');
				$('#btn_admin').show();
			});
		});
		
	})($);
</script>

