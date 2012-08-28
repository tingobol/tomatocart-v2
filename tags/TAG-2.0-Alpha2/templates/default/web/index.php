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
  <?php 
    if (isset($template['module_groups']['header'])) {
      echo $template['module_groups']['header']; 
    } 
  ?> 
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
  <?php 
    if (isset($template['module_groups']['footer'])) {
      echo $template['module_groups']['footer']; 
    } 
  ?> 
  <!--  END: page footer -->
</body>
</html>
