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

require_once 'helpers/general_helper.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <link rel="shortcut icon" href="images/tomatocart.ico" type="image/x-icon" />
    <title><?php echo $template['title'];?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <?php echo $template['meta_tags'];?>
    
    <base href="<?php echo base_url();?>" />
    <link rel="stylesheet" href="<?php echo base_url();?>templates/base/web/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>templates/base/web/css/select2.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>templates/default/web/css/stylesheet.css" />
    <?php echo $template['stylesheets'];?>
    
    <script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/jquery/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/jquery/jquery.loadmask.min.js"></script>
    <?php echo $template['javascripts'];?>
	<script type="text/javascript">
		var base_url = '<?php echo base_url(); ?>';
	</script>
</head>
<body>
  <!--  page header  -->
    <!-- BEGIN: Header -->
    <div id="header">
        <div class="container">
        	<div class="row-fluid">
        		<div class="span4">
                    <a href="<?php echo base_url(); ?>">
                    	<img src="<?php echo image_url('store_logo.png'); ?>" alt="<?php echo config('STORE_NAME'); ?>" title="<?php echo config('STORE_NAME'); ?>" />
                    </a>  
        		</div>
        		<div class="span8">
                    <div class="top-nav row-fluid clearfix">
                    	<div class="pull-right">
                            <a class="popup-cart" href="javascript:void(0);">
                                <i class="icon-shopping-cart"></i>
                                <span id="popup-cart-items"><?php echo cart_item_count(); ?></span>&nbsp;<span><?php echo lang('text_items'); ?></span>
                            </a>
                        </div>
                        <div class="dropdown pull-right">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="javascrip:void(0);"><?php echo currency_title(); ?> (<?php echo currency_symbol_left(); ?>)<b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu">
                            <?php 
                                foreach (get_currencies() as $code => $currency) : 
                            ?>
                                <li role="menuitem">
                                	<a href="<?php echo current_url() . '?currency=' . $code; ?>"><?php echo $currency['title']; ?> (<?php echo $currency['symbol_left']; ?>)</a>
                                </li>
                            <?php 
                                endforeach; 
                            ?>
                            </ul>
                        </div>
                        <div class="dropdown pull-right">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="javascrip:void(0);">
                            	<img src="<?php echo lang_image(); ?>" alt="<?php echo lang_name(); ?>" title="<?php echo lang_name(); ?>" width="16" height="10" /> <?php echo lang_name(); ?><b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                            <?php 
                                foreach (get_languages() as $lang) : 
                            ?>
                                <li role="menuitem">
                                	<a href="<?php echo current_url() . '?language=' . $lang['code']; ?>"><img src="<?php echo image_url('worldflags/' . strtolower(substr($lang['code'], 3)) . '.png'); ?>" alt="<?php echo $lang['name']; ?>" title="<?php echo $lang['name']; ?>" width="16" height="10" /> <?php echo $lang['name']; ?></a>
                                </li>
                            <?php 
                                endforeach; 
                            ?>
                            </ul>
                        </div>
                    </div>
                    <div class="main-nav">
                        <a href="<?php echo base_url(); ?>"><?php echo lang('home'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="<?php echo site_url('account'); ?>"><?php echo lang('my_account'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="<?php echo site_url('wishlist'); ?>"><?php echo lang('my_wishlist'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                    	<a href="<?php echo site_url('checkout/shopping_cart'); ?>"><?php echo lang('cart_contents'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="<?php echo site_url('checkout'); ?>"><?php echo lang('checkout'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php 
                        if (is_logged_on()) : 
                    ?>
                        <a href="<?php echo site_url('account/logoff'); ?>"><?php echo lang('logoff'); ?></a>
                    <?php 
                        else : 
                    ?>
                        <a href="<?php echo site_url('account/login'); ?>"><?php echo lang('login'); ?></a>
                    <?php 
                        endif; 
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Header -->
    
    <!-- BEGIN: Navigation -->
    <div class="container">
    	<div class="navbar navbar-inverse">
    		<div class="navbar-inner">
    			<?php echo build_categories_dropdown_menu(); ?>
                <form name="search_post" method="post" action="<?php echo site_url('search'); ?>" class="navbar-search pull-right">
                    <input type="text" name="keywords" class="search-query" placeholder="Search" />
                    <div class="icon-search"></div>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Navigation -->
    
    <!-- BEGIN: Breadcrumb -->
    <div class="container">
        <ul class="breadcrumb">
        <?php 
            foreach($template['breadcrumbs'] as $breadcrumb) : 
        ?>
            <li><a href="<?php echo $breadcrumb['uri']; ?>"><?php echo $breadcrumb['name']; ?></a><span class="divider">/</span></li>
        <?php 
            endforeach; 
        ?> 
        </ul>       
    </div>
    <!-- END: Breadcrumb -->
<!--  END: page header  -->
  
    <!--  slideshow  -->
    <?php 
        if (isset($template['module_groups']['slideshow'])) {
    ?>
        <div id="slideshows" class="container">
        <?php echo $template['module_groups']['slideshow']; ?>
        </div>
    <?php 
        }
    ?>
    <!--  END: slideshow  -->
    <div class="container">
    	<div class="row-fluid">
        <!--  left module group  -->
        <?php 
            $has_left = isset($template['module_groups']['left']) && !empty($template['module_groups']['left']);
            if ($has_left) 
            {
        ?>
            <div id="content-left" class="span<?php echo $has_left ? 3 : 0; ?>"><?php echo $template['module_groups']['left']; ?></div> 
        <?php 
            }  
        ?>
        <!--  END: left module group  -->
    
        <div  id="content-center" class="span<?php echo 12 - ($has_left ? 3 : 0); ?>">
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
    
        <!--  right module group  -->
        <?php 
            if (isset($template['module_groups']['right'])) 
            {
        ?>
            <div id="content-right" class="span<?php echo isset($template['module_groups']['right']) ? 3 : 0; ?>"><?php echo $template['module_groups']['right']; ?></div> 
        <?php 
            }  
        ?>
        <!--  END: right module group  -->
    	</div>
    </div>
  
<!--  BEGIN: Page Footer -->
<div class="container">
	<div id="footer" class="row-fluid clearfix">
    	<div class="span3">
            <?php 
                if (isset($template['module_groups']['footer-col-1'])):
                    echo $template['module_groups']['footer-col-1']; 
                endif;
            ?>
    	</div>
    	<div class="span3">
            <?php 
                if (isset($template['module_groups']['footer-col-2'])):
                    echo $template['module_groups']['footer-col-2']; 
                endif;
            ?>
    	</div>
    	<div class="span3">
            <?php 
                if (isset($template['module_groups']['footer-col-3'])):
                    echo $template['module_groups']['footer-col-3']; 
                endif;
            ?>
    	</div>
    	<div class="span3">
            <?php 
                if (isset($template['module_groups']['footer-col-4'])):
                    echo $template['module_groups']['footer-col-4']; 
                endif;
            ?>
    	</div>
    </div>
    <p class="copyright pull-right">
    	Copyright &copy; 2011 <a href="index.php">TomatoCart Demo Store</a><br />Powered by <a href="http://www.tomatocart.com" target="_blank">TomatoCart</a>
    </p>
</div>
<!--  END: Page Footer -->

<!--  BEGIN: Run Service -->
<?php 
    run_service('google_analytics'); 
?>

<?php 
    run_service('debug'); 
?>
<!--  END: Run Service -->
  
<script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/bootstrap/select2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>templates/base/web/javascript/toc.js"></script>

<script type="text/javascript">
	$original = $('.navbar .nav > li.active');
	$('.navbar .nav > li').mouseenter(function() {
		$(this).parent().find('li').removeClass('active');
		$(this).addClass('active');
    });
	$('.navbar .nav').mouseleave(function() {
		$(this).parent().find('li').removeClass('active');
		$original.addClass('active');
    });

	$('.dropdown-toggle').dropdown();
	$('.popup-cart').popover({
	    animation: true,
	    trigger: 'hover',
	    placement: 'bottom',
	    html: true,
	    title: '<b><?php echo lang('cart_contents'); ?></b>',
	    content: '<?php echo get_shopping_cart_contents(); ?>'
	});
</script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37863867-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
  <!--  END: page footer -->
</body>
</html>