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
<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="images/tomatocart.ico" type="image/x-icon" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $template['title'];?></title>
    <?php echo $template['meta_tags'];?>
    
    <base href="<?php echo base_url();?>" />
    <link rel="stylesheet"  href="<?php echo base_url();?>templates/base/mobile/javascript/jquery/jquery.mobile-1.1.0.min.css" /> 
    <link rel="stylesheet"  href="<?php echo base_url();?>templates/default/mobile/css/stylesheet.css" /> 
    <?php echo $template['stylesheets'];?>
    
    <script src="<?php echo base_url();?>templates/base/mobile/javascript/jquery/jquery-1.6.1.min.js"></script>
    <script src="<?php echo base_url();?>templates/base/mobile/javascript/jquery/jquery.mobile-1.1.0.min.js"></script>
    <?php echo $template['javascripts'];?>
	<script type="text/javascript">
		var base_url = '<?php echo base_url(); ?>';
	</script>
</head>
<body>
  <div data-role="page" class="type-index">
		<div data-role="header">
    	    <div data-role="navbar" class="nav-glyphish" data-grid="d">
    		    <ul>
                    <li><a href="<?php echo base_url(); ?>" id="tomatocart" data-icon="custom" data-ajax="false">TomatoCart</a></li>
                    <li><a href="<?php echo base_url(); ?>" id="home" data-icon="custom" data-ajax="false"><?php echo lang('home');?></a></li>
                    <?php 
                        if ($is_logged_on):
                    ?>
                    <li><a href="<?php echo site_url('account/logoff');?>" id="logoff" data-icon="custom"><?php echo lang('logoff');?></a></li>
                    <?php 
                        else:
                    ?>
                    <li><a href="<?php echo site_url('account');?>" id="login" data-icon="custom"><?php echo lang('login');?></a></li>
                    <?php 
                        endif;
                    ?>
                    <li><a href="<?php echo site_url('account');?>" id="my-account" data-icon="custom"><?php echo lang('my_account');?></a></li>
                    <li><a href="<?php echo site_url('checkout/shopping_cart');?>" id="item" data-icon="custom"><span id="popupCartItems"><?php echo $items_num; ?></span>&nbsp;<span><?php echo lang('text_items');?></span></a></li>
    		    </ul>
    	    </div>
		</div>
		<div id="content" data-role="content">
        <?php 
            if (isset($template['module_groups']['before'])) :
                echo $template['module_groups']['before']; 
            endif;
        ?>
        
        <?php 
            if (isset($template['body'])):
                echo $template['body']; 
            endif;
        ?>
        <?php 
            if (isset($template['module_groups']['after'])):
                echo $template['module_groups']['after']; 
            endif;
        ?>
		</div>
    <div data-role="footer">
      <p>Copyright &copy; 2012 <a href="index.php">TomatoCart Demo Store</a><br />Powered by <a target="_blank" href="http://www.tomatocart.com">TomatoCart</a></p>
    </div>
  </div>
</body>
</html>
