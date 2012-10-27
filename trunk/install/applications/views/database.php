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
                  	
            
                <form name="install" id="installForm" action="<?php echo site_url('index/index/db_create'); ?>" method="post" onsubmit="prepareDB(); return false;" class="form-horizontal">
                    <div class="info">
                        <div class="control-group">
                            <label class="control-label" for="DB_SERVER"><?php echo lang('param_database_server'); ?>:</label>
                            <div class="controls">
                            	<input type="text" id="DB_SERVER" name="DB_SERVER" <?php if(!empty($DB_SERVER)){echo "value='$DB_SERVER'";} ?> />
                            </div>
                            <div class="description"><?php echo lang('param_database_server_description'); ?></div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="DB_SERVER_USERNAME"><?php echo lang('param_database_username'); ?>:</label>
                            <div class="controls">
                            	<input type="text" id="DB_SERVER_USERNAME" name="DB_SERVER_USERNAME" <?php if(!empty($DB_SERVER_USERNAME)){echo "value='$DB_SERVER_USERNAME'";} ?> />
                            </div>
                            <div class="description"><?php echo lang('param_database_username_description'); ?></div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="DB_SERVER_PASSWORD"><?php echo lang('param_database_password'); ?>:</label>
                            <div class="controls">
                            	<input type="password" id="DB_SERVER_PASSWORD" name="DB_SERVER_PASSWORD" <?php if(!empty($DB_SERVER_PASSWORD)){echo "value='$DB_SERVER_PASSWORD'";} ?> />
                            </div>
                            <div class="description"><?php echo lang('param_database_password_description'); ?></div>
                        </div>
                        <div class="control-group <?php echo (!empty($warn_msg) && $warn_msg=='db_exist') ? 'error':''; ?>">
                            <label class="control-label" for="DB_DATABASE"><?php echo lang('param_database_name'); ?>:</label>
                            <div class="controls">
                            	<input type="text" id="DB_DATABASE" name="DB_DATABASE" <?php if(!empty($DB_DATABASE)){echo "value='$DB_DATABASE'";} ?> />
                            	<span class="help-inline"><?php echo (!empty($warn_msg) && $warn_msg=='db_exist') ? lang('error_duplicate_db'):''; ?></span>
                            </div>
                            <div class="description"><?php echo lang('param_database_name_description'); ?></div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="DB_DATABASE_CLASS"><?php echo lang('param_database_type'); ?>:</label>
                            <div class="controls">
                            	<?php 
                            	    if(empty($DB_DATABASE_CLASS)){
                                	    echo form_dropdown('DB_DATABASE_CLASS', $db_table_types);
                            	    }else{
                                	    echo form_dropdown('DB_DATABASE_CLASS', $db_table_types,$DB_DATABASE_CLASS);                            	        
                            	    }
                            	?>
                            </div>
                            <div class="description"><?php echo lang('param_database_type_description'); ?></div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="DB_TABLE_PREFIX"><?php echo lang('param_database_prefix'); ?>:</label>
                            <div class="controls">
                            	<input type="text" id="DB_TABLE_PREFIX" name="DB_TABLE_PREFIX" <?php if(!empty($DB_TABLE_PREFIX)){echo "value='$DB_TABLE_PREFIX'";}else{echo "value='toc_'";} ?> />
                            </div>
                            <div class="description"><?php echo lang('param_database_prefix_description'); ?></div>
                        </div>
                    </div>

                    <div>
                        <span class="pull-left" id="alert_msg_panel">
                                <?php if(!empty($error_msg)):?>
                                <span class="alert alert-error"><?php echo lang($error_msg); ?></span>
                                <?php elseif (!empty($warn_msg)):?>
                                <span class="alert alert-block"><?php echo lang($warn_msg); ?></span>                                
                                <?php elseif ($create_db_success):?>
                                <span class="alert alert-success"><?php echo lang('msg_create_db_success'); ?></span>                                
                                <?php endif;?>        
                        </span>
                          
                        <span id="btns_checks" class="pull-right" <?php echo (!empty($warn_msg) && $warn_msg=='db_exist') ? '':'style="display: none;"'; ?>>
                       	   <a id="btn_retain" class="btn btn-info"><i class="icon-ok icon-white"></i> &nbsp;<?php echo lang('image_button_retain'); ?></a>
                           <button type="submit" class="btn btn-warning"><i class="icon-ok icon-white"></i> &nbsp;<?php echo lang('image_button_rebuild'); ?></button>
                           <?php if(!empty($warn_msg) && $warn_msg=='db_exist'):?>
                        	   <input type="hidden" name="rebuild" value="yes"/>
                           <?php endif;?>
                        </span>
                        
                        <span id="btns_db" class="pull-right" <?php echo (!empty($warn_msg) && $warn_msg=='db_exist') ? 'style="display: none;"':''; ?>>
                            <?php if($create_db_success):?>
                               	<a href="<?php echo site_url(); ?>" class="btn btn-info" href="<?php echo site_url(); ?>"><i class="icon-remove icon-white"></i> &nbsp;<?php echo lang('image_button_cancel'); ?></a>
                               	<a href="<?php echo site_url('index/index/setting'); ?>" class="btn btn-info"><i class="icon-ok icon-white"></i> &nbsp;<?php echo lang('image_button_continue'); ?></a>
                                <?php else:?>
                               	<a href="<?php echo site_url(); ?>" class="btn btn-info" href="<?php echo site_url(); ?>"><i class="icon-remove icon-white"></i> &nbsp;<?php echo lang('image_button_cancel'); ?></a>
                               	<button type="submit" class="btn btn-info disabled" disabled="true"><i class="icon-plus icon-white"></i> &nbsp;<?php echo lang('image_button_create_db'); ?></button>
                            <?php endif;?>
                       </span>
                    </div>                    
                    
                    <!-- 
                    <div>
                        <span class="span7" id="alert_msg_panel">
                                <?php if(!empty($error_msg)):?>
                                <span class="alert alert-error"><?php echo lang($error_msg); ?></span>
                                <?php elseif (!empty($warn_msg)):?>
                                <span class="alert alert-block"><?php echo lang($warn_msg); ?></span>                                
                                <?php elseif ($create_db_success):?>
                                <span class="alert alert-success"><?php echo lang('msg_create_db_success'); ?></span>                                
                                <?php endif;?>        
                        </span>
                          
                        <span id="btns_checks" <?php echo (!empty($warn_msg) && $warn_msg=='db_exist') ? '':'style="display: none;"'; ?>>
                           <span class="span2 pull-right" style="margin:0 0 0 0; width: 75px;">
                        	   <a id="btn_retain" class="btn btn-info"><i class="icon-ok icon-white"></i> &nbsp;<?php echo lang('image_button_retain'); ?></a>
                           </span>
                           <span class="span2 pull-right" style="margin:0 0 0 0; width: 75px;">
                        	   <button type="submit" class="btn btn-warning"><i class="icon-ok icon-white"></i> &nbsp;<?php echo lang('image_button_rebuild'); ?></button>
                               <?php if(!empty($warn_msg) && $warn_msg=='db_exist'):?>
                        	       <input type="hidden" name="rebuild" value="yes"/>
                        	   <?php endif;?>
                           </span>
                        </span>
                        <?php if(!empty($warn_msg) && $warn_msg=='db_exist'):?>
                        <?php else:?>
                            
                        <?php endif?>
                        <span id="btns_db" <?php echo (!empty($warn_msg) && $warn_msg=='db_exist') ? 'style="display: none;"':''; ?>>
                            <?php if($create_db_success):?>
                                <span class="span2 pull-right" style="margin:0 0 0 0; width: 110px;">
                                	<a href="<?php echo site_url('index/index/webserver'); ?>" class="btn btn-info"><i class="icon-ok icon-white"></i> &nbsp;<?php echo lang('image_button_continue'); ?></a>
                                </span>
                                <span class="span2 pull-right" style="margin-top: 0px;">                        
                                   	<a href="javascript:void(0);" class="btn btn-info" href="<?php echo site_url(); ?>"><i class="icon-remove icon-white"></i> &nbsp;<?php echo lang('image_button_cancel'); ?></a>
                                </span>
                                <?php else:?>
                                <span class="span2 pull-right" style="margin:0 0 0 0;width: 125px;">
                                	<button type="submit" class="btn btn-info disabled" disabled="true"><i class="icon-plus icon-white"></i> &nbsp;<?php echo lang('image_button_create_db'); ?></button>
                                </span>
                                <span class="span2 pull-right" style="margin-top: 0px;">                        
                                   	<a href="javascript:void(0);" class="btn btn-info" href="<?php echo site_url(); ?>"><i class="icon-remove icon-white"></i> &nbsp;<?php echo lang('image_button_cancel'); ?></a>
                                </span>
                            <?php endif;?>
                       </span>
                    </div>
                     -->
                </form>
            </div>
    	</div>
</div>
<script type="text/javascript">
        (function($){
            
            $('#installForm').delegate('input','keyup blur',function(){
                var is_fill=false;
            	var flds = $('#installForm').find('input');
                var size = flds.size();
                for(var i=0;i<size;i++){
                    if(!$(flds[i]).val()){
                        is_fill = false;
                        break;
                    }
                    if(i==size-1){
                        is_fill = true;
                    }
                }
                //console.log(is_fill);
                if(!is_fill){
                    $('button[type="submit"]').addClass('disabled');
                    $('button[type="submit"]').attr('disabled',true);
                }else{
                    $('button[type="submit"]').removeClass('disabled');
                    $('button[type="submit"]').removeAttr('disabled');
                }
             });


            $('#btn_retain').on('click',function(){
               $('#btns_checks').hide(); 
               $('#btns_db').show();

               $('#alert_msg_panel').children('span').html('<?php echo lang('msg_change_db_name')?>');
            });
                
        })($);
</script>



