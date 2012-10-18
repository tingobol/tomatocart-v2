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

  $db_table_types = array('mysql'=> 'MySQL - MyISAM (Default)',
                          'mysql_innodb'=> 'MySQL - InnoDB (Transaction-Safe)',
                          'mysqli'=> 'MySQLi (PHP 5 / MySQL 4.1)');
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
          	<h1><?php echo lang('page_title_database_server_setup'); ?></h1>
          
          	<p><?php echo lang('text_database_server_setup'); ?></p>
                  	
            
                <form name="install" id="installForm" action="<?php echo site_url('index/index/save_db'); ?>" method="post" onsubmit="prepareDB(); return false;" class="form-horizontal">
                    <div class="info">
                        <div class="control-group">
                            <label class="control-label" for="DB_SERVER"><?php echo lang('param_database_server'); ?>:</label>
                            <div class="controls">
                            	<input type="text" id="DB_SERVER" name="DB_SERVER" />
                            </div>
                            <div class="description"><?php echo lang('param_database_server_description'); ?></div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="DB_SERVER_USERNAME"><?php echo lang('param_database_username'); ?>:</label>
                            <div class="controls">
                            	<input type="text" id="DB_SERVER_USERNAME" name="DB_SERVER_USERNAME" />
                            </div>
                            <div class="description"><?php echo lang('param_database_username_description'); ?></div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="DB_SERVER_PASSWORD"><?php echo lang('param_database_password'); ?>:</label>
                            <div class="controls">
                            	<input type="password" id="DB_SERVER_PASSWORD" name="DB_SERVER_PASSWORD" />
                            </div>
                            <div class="description"><?php echo lang('param_database_password_description'); ?></div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="DB_DATABASE"><?php echo lang('param_database_name'); ?>:</label>
                            <div class="controls">
                            	<input type="password" id="DB_DATABASE" name="DB_DATABASE" />
                            </div>
                            <div class="description"><?php echo lang('param_database_name_description'); ?></div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="DB_DATABASE_CLASS"><?php echo lang('param_database_type'); ?>:</label>
                            <div class="controls">
                            	<?php 
                            	    echo form_dropdown('DB_DATABASE_CLASS', $db_table_types);
                            	?>
                            </div>
                            <div class="description"><?php echo lang('param_database_type_description'); ?></div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="DB_TABLE_PREFIX"><?php echo lang('param_database_prefix'); ?>:</label>
                            <div class="controls">
                            	<input type="text" id="DB_TABLE_PREFIX" name="DB_TABLE_PREFIX" value="toc_" />
                            </div>
                            <div class="description"><?php echo lang('param_database_prefix_description'); ?></div>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls pull-right">
                        	<a href="javascript:void(0);" class="btn btn-info" href="<?php echo site_url(); ?>"><i class="icon-remove icon-white"></i> &nbsp;<?php echo lang('image_button_cancel'); ?></a>
                			<a href="<?php echo site_url('index/index/webserver'); ?>" class="btn btn-info"><i class="icon-ok icon-white"></i> &nbsp;<?php echo lang('image_button_continue'); ?></a>
                        </div>
                    </div>
                </form>
            </div>
    	</div>
	</div>
</div>