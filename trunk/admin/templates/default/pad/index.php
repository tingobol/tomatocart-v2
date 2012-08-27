<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
  $Id: index.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com

  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html dir="<?php echo lang_get_text_direction(); ?>" xml:lang="<?php echo lang_get_code(); ?>" lang="<?php echo lang_get_code(); ?>">
  <head>
    <title><?php echo lang('administration_title'); ?></title>
    <link rel="shortcut icon" href="<?php echo base_url();?>images/tomatocart.ico" type="image/x-icon" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="-1" />
    <?php echo $template['meta_tags'];?>
    
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>templates/base/web/javascript/extjs/resources/css/ext-all.css" />
    <?php echo $template['stylesheets'];?>

		<script src="<?php echo base_url();?>/templates/base/web/javascript/extjs/ext-all-debug.js"></script> 
    <?php echo $template['javascripts'];?>
  </head>

  <body scroll="no">
    <?php 
      echo $template['body'];
    ?>
  </body>
</html>