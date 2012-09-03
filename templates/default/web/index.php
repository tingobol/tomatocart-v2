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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="en_US" lang="en_US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="images/tomatocart.ico" type="image/x-icon" />
    <title><?php echo $template['title'];?></title>
    <?php echo $template['meta_tags'];?>
    
    <base href="<?php echo base_url();?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>templates/default/web/css/stylesheet.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>templates/default/web/css/ui-lightness/jquery-ui-1.8.14.custom.css" />
    <?php echo $template['stylesheets'];?>
    
    <script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/jquery-1.5.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/toc.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/jquery.loadmask.min.js"></script>
    <?php echo $template['javascripts'];?>
	<script type="text/javascript">
		var base_url = '<?php echo base_url(); ?>';
	</script>
</head>
<body>
  <!--  page header  -->
    <!-- Begin: Header -->
    <div id="header">
        <ul>
            <li><a href="<?php echo site_url('account/wishlist'); ?>"><?php echo lang('my_wishlist'); ?></a></li>
            
        <?php 
            if ($is_logged_on) : 
        ?>
            <li><a href="<?php echo site_url('account/logoff'); ?>"><?php echo lang('logoff'); ?></a></li>
        <?php 
            else : 
        ?>
            <li><a href="<?php echo site_url('account/login'); ?>"><?php echo lang('login'); ?></a></li>
            <li><a href="<?php echo site_url('account/create'); ?>"><?php echo lang('create_account'); ?></a></li>
        <?php 
            endif; 
        ?>
            
            <li id="bookmark"></li>    
            <li class="cart">
                <a href="<?php echo site_url('shopping_cart'); ?>">
                    <span id="popupCart">
                        <img src="images/shopping_cart_icon.png" />
                        <span id="popupCartItems"><?php echo $items_num; ?></span><span><?php echo lang('text_items'); ?></span>
                    </span>
                </a>
            </li>
        </ul>
        <a href="<?php echo base_url(); ?>" id="siteLogo">
        	<img src="<?php echo image_url('store_logo.png'); ?>" border="0" alt="<?php echo config('STORE_NAME'); ?>" title="<?php echo config('STORE_NAME'); ?>" />
        </a>  
    </div>
    <!-- End: Header -->
    
    <!-- Begin: Navigation -->
    <div id="nav">
        <div id="navigation">
            <ul>
                <li class="current"><a href="<?php echo base_url(); ?>"><?php echo lang('home'); ?></a></li>
                <li><a href="<?php echo site_url('latest'); ?>"><?php echo lang('new_products'); ?></a></li>
                <li><a href="<?php echo site_url('specials'); ?>"><?php echo lang('specials'); ?></a></li>
                <li><a href="<?php echo site_url('account'); ?>"><?php echo lang('my_account'); ?></a></li>
                <li><a href="<?php echo site_url('checkout'); ?>"><?php echo lang('checkout'); ?></a></li>
                <li><a href="<?php echo site_url('contact_us'); ?>"><?php echo lang('contact_us'); ?></a></li>      
            </ul>
            <div style="float: right; width: 206px">
                <form name="search_post" method="post" action="<?php echo site_url('search'); ?>">
                    <p class="keywords">
                        <input type="text" name="keywords" id="keywords" />
                        <img id="quickSearch" class="search" src="images/button_quick_find.png" title="search" alt="search" />
                    </p>
                </form>
                
            </div>
        </div>
    </div>
    <!-- End: Navigation -->
    
    <!-- Begin: Breadcrumb -->
    <div id="breadcrumb">
    <?php 
        foreach($template['breadcrumbs'] as $breadcrumb) : 
    ?>
        <a href="<?php echo $breadcrumb['uri']; ?>"><?php echo $breadcrumb['name']; ?></a> &raquo; 
    <?php 
        endforeach; 
    ?>        
        
        <div id="languages">
        <?php 
            foreach ($languages as $language) : 
        ?>
            <a href="<?php echo $language['url']; ?>">
            	<img src="<?php echo $language['image']; ?>" border="0" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" width="16" height="10" />
            </a>
        <?php 
            endforeach; 
        ?>
        </div>
    </div>
    <!-- End: Breadcrumb -->
    <script type="text/javascript">
        $('#quickSearch').bind('click', function() {
            $('#navigation form').submit();
        });
    </script>
  <!--  END: page header  -->
  
  <!--  slideshow  -->
  <?php 
    if (isset($template['module_groups']['slideshow'])) {
  ?>
  <div class="wrapper">
    <?php echo $template['module_groups']['slideshow']; ?>
  </div>
  <?php 
    }
  ?>
  <!--  END: slideshow  -->
  
  <div class="wrapper">
    <div id="col-left">
      <div class="box-group">
         <!--  left module group  -->
         <?php 
          if (isset($template['module_groups']['left'])) {
            echo $template['module_groups']['left']; 
          }  
         ?>
         <!--  END: left module group  -->
      </div>
    </div>

    <div id="main">
        <!--  before module group  -->
        <?php 
          if (isset($template['module_groups']['before'])) {
            echo $template['module_groups']['before']; 
          }  
        ?>
        <!--  END: before module group  -->
        
        <!--  page body  -->
        <?php 
          if (isset($template['body'])) {
            echo $template['body']; 
          }  
        ?>
        <!--  END: page body  -->
        
        <!--  after module group  -->
        <?php 
          if (isset($template['module_groups']['after'])) {
            echo $template['module_groups']['after']; 
          }  
        ?>
        <!--  END: after module group  -->
    </div>
    <div id="col-right">
      <div class="box-group">
          <!--  right module group  -->
          <?php 
            if (isset($template['module_groups']['right'])) {
              echo $template['module_groups']['right']; 
            }  
          ?>
          <!--  END: right module group  -->
      </div>
    </div>
  </div>
  
  <!--  page footer -->
<div id="footer">
    <ul>
        <li><a href="<?php echo site_url(); ?>"><?php echo lang('home'); ?></a><span>|</span></li>
        <li><a href="<?php echo site_url('specials'); ?>"><?php echo lang('specials'); ?></a><span>|</span></li>
        <li><a href="<?php echo site_url('latest'); ?>"><?php echo lang('new_products'); ?></a><span>|</span></li>
        <li><a href="<?php echo site_url('account'); ?>"><?php echo lang('my_account'); ?></a><span>|</span></li>
        <li><a href="<?php echo site_url('account/wishlist'); ?>"><?php echo lang('my_wishlist'); ?></a><span>|</span></li>
        <li><a href="<?php echo site_url('checkout/shopping_cart'); ?>"><?php echo lang('cart_contents'); ?></a><span>|</span></li>
        <li><a href="<?php echo site_url('checkout/checkout'); ?>"><?php echo lang('checkout'); ?></a><span>|</span></li>
        <li><a href="<?php echo site_url('contact_us'); ?>"><?php echo lang('contact_us'); ?></a><span>|</span></li>
        <li><a href="<?php echo site_url('info/guestbooks'); ?>"><?php echo lang('guest_book'); ?></a><span>|</span></li>
        <li><a href="<?php echo site_url('rss'); ?>"><img src="images/rss16x16.png" border="0" alt="" /><span><?php echo lang('rss_heading'); ?></span></a></li>    
    </ul>
    <div style="clear:both"></div>
    <p>
    	Copyright &copy; 2011 <a href="index.php">TomatoCart Demo Store</a><br />Powered by <a href="http://www.tomatocart.com" target="_blank">TomatoCart</a>
    </p>
</div>
  <!--  END: page footer -->
</body>
</html>
